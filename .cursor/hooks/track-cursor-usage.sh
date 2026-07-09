#!/bin/sh
# Wrapper for the Cursor usage stop hook.
#
# Cursor spawns hooks with a minimal PATH (especially GUI launches), so a bare
# `node` is often not found for nvm/volta/homebrew installs. This locates a node
# binary robustly, then execs the tracker script with stdin passed through.
# Always exits 0 so it never blocks the agent loop.

DIR=$(cd "$(dirname "$0")" && pwd)
LOG="$DIR/usage-hook.log"

NODE_BIN=""
if command -v node >/dev/null 2>&1; then
    NODE_BIN=$(command -v node)
else
    for candidate in \
        /opt/homebrew/bin/node \
        /usr/local/bin/node \
        "$HOME/.volta/bin/node" \
        "$HOME/.nvm/versions/node/"*/bin/node \
        /usr/bin/node; do
        if [ -x "$candidate" ]; then
            NODE_BIN="$candidate"
            break
        fi
    done
fi

if [ -z "$NODE_BIN" ]; then
    printf '%s node not found on PATH; usage not recorded\n' "$(date -u +%FT%TZ)" >>"$LOG" 2>/dev/null
    printf '{}'
    exit 0
fi

exec "$NODE_BIN" "$DIR/track-cursor-usage.mjs"
