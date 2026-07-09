#!/usr/bin/env node
// Cursor `stop` hook: forwards per-turn inference usage to the time system.
//
// Reads the stop-hook payload from stdin, attaches the project id, and POSTs it
// to the ingest endpoint. Fire-and-forget: always exits 0, never blocks the
// agent loop, and appends a one-line result to usage-hook.log for debugging.
//
// Configuration is resolved (highest priority first) from:
//   1. Environment: TIME_USAGE_INGEST_URL, TIME_USAGE_INGEST_TOKEN, TIME_USAGE_PROJECT_ID
//   2. .cursor/time-usage.local.json (untracked)  { ingest_url, ingest_token, project_id }
//   3. .cursor/time-usage.json (committed)        { project_id, ingest_url }
//   4. Defaults: ingest_url = http://localhost:8095/api/cursor-usage
//
// Only a token is strictly required (no default, since it is a secret). Put it
// in .cursor/time-usage.local.json as { "ingest_token": "..." } for local dev.

import { appendFileSync, readFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const TIMEOUT_MS = 4000;
const DEFAULT_URL = 'https://capsule.prescottandwest.com/api/cursor-usage';

const HOOK_DIR = dirname(fileURLToPath(import.meta.url));
const CURSOR_DIR = dirname(HOOK_DIR);
const LOG_PATH = join(HOOK_DIR, 'usage-hook.log');

function log(message) {
    try {
        appendFileSync(LOG_PATH, `${new Date().toISOString()} ${message}\n`);
    } catch {
        // Logging must never break the hook.
    }
}

function readStdin() {
    try {
        return readFileSync(0, 'utf8');
    } catch {
        return '';
    }
}

function safeParse(json, fallback) {
    try {
        return JSON.parse(json);
    } catch {
        return fallback;
    }
}

function readJsonFile(path) {
    try {
        return safeParse(readFileSync(path, 'utf8'), {});
    } catch {
        return {};
    }
}

function normalizeProjectId(value) {
    const id = Number(value);

    return Number.isFinite(id) && id > 0 ? id : null;
}

function resolveConfig() {
    const committed = readJsonFile(join(CURSOR_DIR, 'time-usage.json'));
    const local = readJsonFile(join(CURSOR_DIR, 'time-usage.local.json'));

    return {
        url:
            process.env.TIME_USAGE_INGEST_URL ||
            local.ingest_url ||
            committed.ingest_url ||
            DEFAULT_URL,
        token: process.env.TIME_USAGE_INGEST_TOKEN || local.ingest_token || committed.ingest_token || null,
        projectId: normalizeProjectId(
            process.env.TIME_USAGE_PROJECT_ID ?? local.project_id ?? committed.project_id,
        ),
    };
}

function done() {
    process.stdout.write('{}');
    process.exit(0);
}

async function main() {
    const { url, token, projectId } = resolveConfig();

    if (!token) {
        log('skipped: no ingest token configured (set ingest_token in .cursor/time-usage.local.json)');

        return done();
    }

    const payload = safeParse(readStdin(), null);

    if (!payload || typeof payload !== 'object') {
        log('skipped: empty or invalid stdin payload');

        return done();
    }

    const body = { ...payload, project_id: projectId };
    const summary = `model=${payload.model ?? '?'} project_id=${projectId ?? 'null'}`;

    const controller = new AbortController();
    const timer = setTimeout(() => controller.abort(), TIMEOUT_MS);

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-Ingest-Token': token,
            },
            body: JSON.stringify(body),
            signal: controller.signal,
        });

        if (response.ok) {
            log(`sent: ${summary} -> HTTP ${response.status}`);
        } else {
            log(`error: ${summary} -> HTTP ${response.status} (${url})`);
        }
    } catch (error) {
        log(`error: ${summary} -> ${error?.name === 'AbortError' ? 'timeout' : error?.message ?? 'request failed'} (${url})`);
    } finally {
        clearTimeout(timer);
    }

    done();
}

main().catch((error) => {
    log(`error: unexpected ${error?.message ?? error}`);
    done();
});
