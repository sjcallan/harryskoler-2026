<script setup lang="ts">
import SiteLayout from '@/layouts/SiteLayout.vue';
import ReviewsSection from '@/components/home/ReviewsSection.vue';
import RadioSection from '@/components/home/RadioSection.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps<{ slug: string }>();

interface Album {
    slug: string;
    title: string;
    subtitle?: string;
    year: string;
    label: string;
    catalogNumber?: string;
    cover: string;
    tracks: string[];
    musicians: string[];
    description: string[];
    spotifyEmbed: string;
    bandcampUrl?: string;
    appleMusicUrl?: string;
    artwork?: string;
    story?: { heading?: string; paragraphs?: string[]; sections?: { subheading: string; paragraphs: string[] }[] };
}

const albums: Album[] = [
    {
        slug: 'echoes',
        title: 'Echoes',
        year: '2025',
        label: 'Red Brick Hill',
        catalogNumber: 'RBH 2131',
        cover: '/images/albums/echoes.png',
        tracks: [
            'Evocation*', 'Study in Orange', 'JW, Michelangelo & the 40 Cent Burger',
            'Everything\'s Cool, Everything\'s Cool!', 'Marian', 'Reminiscence*',
            'Thank You', 'Waiting Patiently', 'Counterpart', 'Sea of Feeling',
            'POW!', 'Overtone', 'Never Played in Syracuse!', 'Allusion*',
        ],
        musicians: [
            'Harry Skoler — Clarinet',
            'Bill Frisell — Guitar',
            'Dezron Douglas — Bass',
            'Johnathan Blake — Drums',
        ],
        description: [],
        spotifyEmbed: '',
        artwork: 'Illustration and layout by Julian Montague',
        story: {
            heading: 'The Stories Behind Echoes',
            sections: [
                {
                    subheading: 'Teddy Wilson',
                    paragraphs: [
                        'When I was fourteen I saw Teddy Wilson in Upstate New York. This was the first time I heard live jazz.',
                        'He was playing in a tiny restaurant with bass.',
                        'I was seated at a table next to the piano, just a few feet from him, his left hand playing stride a few inches from me.',
                        'Nobody was listening, all loudly talking. I was amazed by his playing, and was silent.',
                        'After each solo, I applauded, aghast that I might have been the only one applauding, or one of few.',
                        'After I applauded, each and every time, he turned his head towards me and said "Thank You."',
                        'That one concert changed my life. The next morning, as I delivered my newspapers, in my cloth bag that held the papers, was the cassette player that played the music I had heard the night before. Streets were silent and people were asleep in their homes.',
                        'Stompin\' At The Savoy played out of my newspaper bag the entire route.',
                        'I still have the tape.',
                    ],
                },
                {
                    subheading: 'Rahsaan Roland Kirk',
                    paragraphs: [
                        'In 1975 I saw Rahsaan Roland Kirk at the Jazz Workshop in Boston. If you got there early, you could sit in the front row, and for 3 1/2 dollars, you could watch all three sets. Sometimes you were able to talk to the musicians.',
                        'On this night, I arrived early to find Rahsaan Roland Kirk practicing one of the early electronic wind instruments, which I know now was a Lyricon. It was a metal tube connected to an amp, and I\'d never seen anything like it. I approached him, and asked if it was some kind of an electric saxophone.',
                        'He started screaming "I don\'t play electric saxophone!!! I play natural saxophone!!! I play natural saxophone!!!"',
                        'I was horrified, and felt so badly that I offended him!',
                        'Later that evening, during the set, Rahsaan stopped playing so that the piano could take a solo, and being in the front row at the table, I called out to him since I was just a few feet away.',
                        'I said "I\'m sorry, I didn\'t know what to call it."',
                        'He then brought his arm through the air until he could find my arm, and started patting it and said "That\'s all right\u2026..Everything\'s cool! Everything\'s cool!"',
                        'I can\'t even begin to tell you the relief that I felt.',
                        'Everything was cool.',
                    ],
                },
                {
                    subheading: 'Miles Davis',
                    paragraphs: [
                        'When I graduated from New England Conservatory in 1986, Miles Davis was given an honorary doctorate. He was dressed in this ultra cool suit that just was amazing.',
                        'The students were going up to him and getting their diplomas signed, however, I still had to finish one test, and so I walked without actually having my diploma. I did walk over to him though, and mentioned that I had seen him perform a few years prior in Syracuse.',
                        'At that time, Miles was known for saying opposites. If someone asked him something that they thought he did, he said he didn\'t, and if they mentioned that he didn\'t do something, he said that he did.',
                        'His answer to me was:',
                        '"I never played in Syracuse."',
                        'He then walked out of the door, got into his limo, and took off.',
                        'Obviously, my memory must\u2019ve been wrong.',
                    ],
                },
                {
                    subheading: 'James Williams',
                    paragraphs: [
                        'In my first year at Berklee, I was 18 years old and not very knowledgeable about musicians past the swing era.',
                        'Across the street from one of Berklee\'s buildings was a burger joint, and every time I walked in, the guy that owned it would say in an exaggerated voice, "Michelangelo!!!!"',
                        'I probably didn\'t even know who Michelangelo was, but I did have long hair and a beard, so he welcomed me like that every time I walked in to get a burger.',
                        'The burgers cost 40 cents, and were loaded with pepper, and then when you got the toppings, he would take spoonfuls of tomatoes, lettuce, onions, pickles, and throw them all over the burger. That particular day I had 2 of those scrumptious burgers, and I was in heaven, to say the least.',
                        'They only had a few tables and I was lucky to get one. As I sat there eating my 40 cent burgers, a man came over to me and asked if he could join me. I said yes, and he sat and started eating his 40 cent burgers. He told me that he was James Williams, and started telling me stories.',
                        'I didn\'t know who James Williams was, or Art Blakey, who he played with, but he talked to me as if I was the most important person in the world.',
                        'To this day, it was the best lunch that I ever had.',
                    ],
                },
                {
                    subheading: 'Benny Goodman',
                    paragraphs: [
                        'On my 15th birthday, my parents told me that we were going to drive from Syracuse to New York City to see Benny Goodman at the Rainbow Grill at Rockefeller Center.',
                        'I was surprised and happy, mostly by the fact that Benny Goodman was still alive, which I didn\'t know was the case. I loved Benny Goodman\u2026his playing was really responsible for my getting into clarinet.',
                        'When we got to the Rainbow Grill, I was overwhelmed, and a bit nervous. I actually got to witness Benny Goodman giving his famous "Ray", which was an unapproving stare at a musician that had made some kind of mistake.',
                        'The music was wonderful, and during the break, he sat on stage on a stool, staring straight ahead. To his right were about 10 or so people in a line, all waiting for an autograph.',
                        'Benny never acknowledged them, and nobody got an autograph.',
                        'I decided I would take my booklet from a recent album of his, and stand at the back of the club and wait until he was finished for the night.',
                        'On his last song, Benny did notice me at the back of the club and started playing to me, and gave me a wink. I was kind of terrified.',
                        'At the end of the song, Benny walked off the stage and came over to me, quite the opposite of the way he had treated all the other fans waiting for an autograph earlier.',
                        'As he was signing my booklet, he said in his thick Chicago drawl:',
                        '"YOU\'VE been waiting very PATIENTLY!"',
                    ],
                },
                {
                    subheading: 'Marian McPartland',
                    paragraphs: [
                        'Marian McPartland was, perhaps, the gentlest soul that I ever met in my young life. The first time I had seen her play, at a club called The Dinkler, in Syracuse, I asked her to play a favorite of mine from one of her records, a song called Bohemia After Dark, by Oscar Pettiford.',
                        'She practiced it for just a second, but knew it cold, and proceeded to play that for me. Some years later, she invited me to sit in with her and her legendary husband, Jimmy McPartland. I played Over the Rainbow, and I don\'t think I played it very well, but she was very positive about my playing.',
                        'Many years later, I saw her in New York City. I was sitting fairly far away from the stage, and I was amazed, since she looked to be so energetic and strong that her energy belied her age. It looked like she was thirty years old and she was absolutely astounding. I especially remember my impression of the strength in her arms and hands.',
                        'At the end of the concert, I walked over to her and shook her hand, and told her about the time when she had played Bohemia After Dark for me.',
                        'I was really taken by the fact that her hands actually were quite frail, and were the opposite of what I had seen while she was playing piano during the concert.',
                        'I learned a lot from how she played, and perhaps more importantly, how she spoke to me.',
                    ],
                },
                {
                    subheading: 'Bill Evans',
                    paragraphs: [
                        'Before my first semester at Berklee in 1975, I worked at a resort in Lake George, NY called Blue Water Manor. I was a bellhop during the day and was part of a jazz quintet at night.',
                        'The owner\'s daughter dated Jeremy Steig, the jazz flutist. I didn\'t know who he was, but that summer had many musical adventures with him. He brought as visitors to hang at the resort jazz artists Eddie Gomez, Mike Nock and others.',
                        'I was having a conversation with Eddie Gomez where he was telling me he played with Benny Goodman at Carnegie Hall at the age of seventeen. When I told him I was going to study at Berklee in a couple of weeks, he said "Come see me with Bill Evans at the Jazz Workshop."',
                        'I said "Ok, Eddie."',
                        'I had no idea who Bill Evans was, or for that matter, Eddie Gomez.',
                        'I arrived at the Jazz Workshop in Boston (my first semester at Berklee in Fall of 1975) with my buddy Mark, who had played saxophone in the band with me at the resort, and Eddie put a tape deck on our table, telling us "You can stay all night, tell them you\'re my brother."',
                        'We sat and listened to the Bill Evans trio and I felt like a dope! I had no idea that Eddie Gomez was a renowned bass player with Bill Evans, an iconic pianist.',
                        'Someone directed us to the men\'s room, telling us Eddie and Bill used that as the Green Room.',
                        'We entered all enthusiastic to tell Eddie how great the music was. I think I was way too timid to approach Bill Evans at all. Bill Evans, standing a few feet away, with his orange checkered jacket, gave us a look that I could not figure out. It was a strange moment. He was just looking at us. He was, I think, studying us.',
                        'Ironically, 50 years later, if a student doesn\'t know who Bill Evans and Eddie Gomez are, I just can\'t fathom it!',
                    ],
                },
                {
                    subheading: 'Lionel Hampton',
                    paragraphs: [
                        'When I was in high school, I mostly listened to the swing era and big bands\u2026 They were still on the road, at least some of them, and I enjoyed seeing Count Basie, Woody Herman and Lionel Hampton\'s big bands. When I saw Lionel Hampton, I was amazed to see him go behind the drum set.',
                        'POW !',
                        'POW ! POW !',
                        'POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW ! POW !',
                        '\uD83D\uDCA5 POW!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\uD83D\uDCA5',
                    ],
                },
                {
                    subheading: 'Jimmy Giuffre',
                    paragraphs: [
                        'When I studied at New England Conservatory in the 1980\'s, I studied clarinet, saxophone, flute, ensembles, and composition with Jimmy Giuffre. I also was his assistant at the college, and we became friends.',
                        'The lessons were somewhat mysterious, and I never really did get past four measures in any lesson. He frequently told me that there was no joy in my playing. It wasn\'t the easiest of experiences, but much of what I ended up using for the rest of my life came from Jimmy\'s wisdom and brilliance.',
                        'When I listened to his free playing on the album Free Fall with Steve Swallow and Paul Bley, there was a paragraph on the back cover that referenced Jimmy\'s influences\u2026 it was an enormous paragraph that made me feel awestruck.',
                        'I thought, "I have to ask Jimmy what expression is all about."',
                        'I figured his answer would go on for days.',
                        'During my next composition lesson with him, I asked Jimmy what expression was all about.',
                        'Jimmy said:',
                        '"There\'s a sea of feeling. Then you step into it."',
                    ],
                },
            ],
        },
    },
    {
        slug: 'red-brick-hill',
        title: 'Red Brick Hill',
        year: '2024',
        label: 'Sunnyside Records',
        catalogNumber: 'SSC 1748',
        cover: '/images/albums/red-brick-hill-md.png',
        tracks: [
            'Last Star, Last Night (prologue)', 'NanCee', 'ascent', 'blue, mostly',
            'abyss', 'Last Star, Last Night', 'AppleHands', 'Beneath Bequeath',
            'harbinger', 'here. still.', 'Red Brick Hill', 'furthering',
            'still. here.', 'Last Star, Last Night (epilogue)',
        ],
        musicians: [
            'Harry Skoler — Clarinet',
            'Joel Ross — Vibes',
            'Dezron Douglas — Bass',
            'Johnathan Blake — Drums',
            'Christian Sands — Piano',
            'Marquis Hill — Trumpet',
            'Grégoire Maret — Harmonica',
        ],
        description: [],
        spotifyEmbed: 'https://open.spotify.com/embed/album/5lrVRv2GNNULRBMO9wWOob',
        bandcampUrl: 'https://harryskoler.bandcamp.com/album/red-brick-hill',
        appleMusicUrl: 'https://music.apple.com/us/album/red-brick-hill/1755928110',
        artwork: 'Artwork by Julian Montague',
        story: {
            heading: 'The Story Behind Red Brick Hill',
            paragraphs: [
                'Things of beauty can come from the release of one\'s traumas. Harry Skoler experienced the loss of a friend as a teenager, a passing that deeply impacted the clarinetist/composer and left an indelible mark on his person. On his new recording, Red Brick Hill, Skoler revisits the past and delivers the story instrumentally with an incredible array of musicians in an effort to cleanse his soul, keep his friend\'s legacy alive, and to, hopefully, help others with such burdens through the gift of music.',
                'Skoler grew up in a house his father designed at the top of a red brick hill. Though there weren\'t many children in their neighborhood, Skoler was able to develop deep friendships with a young girl named Nancy and a quiet, serious boy named Bill. When they were teenagers, Bill moved away from the hill but first brought Skoler a box of his childhood toys and trinkets. It wasn\'t until later that Skoler was walking with a friend and saw Bill\'s mother pulling into his friend\'s house.',
                'Curious as to her appearance, Skoler asked his friend about Bill. His friend told Skoler that he wasn\'t supposed to tell him anything. Pressing the friend, Skoler found out that Bill was dead. Running back to his house in a frenzy, Skoler dove into the past weeks\' newspapers, only to find an article relating Bill\'s suicide. Devastated, Skoler received no solace from his parents and found that they had even asked that others not tell him of Bill\'s demise.',
                'The loss and the actions of those around him had lasting effects on Skoler. It was only through making music that he was able to deal with the loss and feel the spirit of his lost friend. Through time and with help, Skoler was able to confront his emotions and find appropriate outlets to express them. In 2022 Skoler began work on a new project and it was producer Walter Smith III who encouraged Skoler\'s decision to take the story and work it into a musical narrative.',
                'In August 2022, Skoler and his son went to New York City to record the album at Sear Sound. Prior to the session, Skoler told the story to which his son replied that Skoler should tell the ensemble that he and Smith had assembled, which included vibraphonist Joel Ross, bassist Dezron Douglas, and drummer Johnathan Blake, the story. The musicians listened to the story with empathy and promised to tell the tale through their playing. The recording evidences the ensemble\'s empathy, as the music highlights the ensemble\'s connection throughout.',
                'The recording is programmatic. Skoler first introduces his first friendship with "NanCee," a bittersweet theme song for his dear friend led by Ross\'s resonant vibes. The lovely ballad "Last Star, Last Night" reflects on how even though there are millions of stars in the sky, like people on earth, it may be that star\'s last moment, as Bill probably realized it was near his last moment when no one else could. The piece also features a touching guest appearance by pianist Christian Sands. "ascent" is the first of three duo pieces. This duet with Douglas represents Bill\'s walk up the hill with the box to deliver to Skoler. Marquis Hill adds his emotive trumpet to Skoler\'s strident clarinet frontline on the emotionally diminished "blue, mostly," while "abyss" is a thoughtful duo between Skoler and Blake, interpreting Skoler\'s frantic run up the snow-covered red brick hill upon learning of his friend\'s shocking loss.',
                'The delicate "AppleHands" is for Nancy and warmly remembers an image of the two friends together, holding apples. Though he took his friend\'s childhood things, Skoler didn\'t know his friend\'s hidden agenda in giving them, as many suicidal people give their belongings away, thus "Beneath Bequeath" refers to this and delivers one of the album\'s most profound musical statements. Though there were signs in Bill\'s demeanor and actions, nobody recognized them. Skoler\'s duet with Ross, "harbinger," gives thoughtful reflection on this.',
                'The sullen "here. still." reflects on Skoler\'s lack of coping mechanisms as a teenager and wondering "Why am I still here?" After some time with therapy, Skoler had a vision in which his current self watched his younger self running up the red brick hill and discovering the newspaper where the sad truth was printed. Upon seeing his past reaction, Skoler embraced his younger self, finally giving himself the compassion and comfort he needed. This is reflected in the standout track, "Red Brick Hill." After decades of torment, Skoler finally found a way to comfort himself, mainly in playing music, when he feels closest to Bill. "still. here." is meant as a reminder that Skoler still lives in the moment and his friend still lives on in his sound. Grégoire Maret lent the piece an additional buoyancy through his dynamic harmonica playing.',
                'Though the story behind Red Brick Hill comes from a place of great hurt, Harry Skoler has created a gorgeous and redemptive album of heartfelt music. Having hidden the story for over five decades, the process of ridding that weight has been essential to Skoler and he hopes that the outcome, namely the music of Red Brick Hill, will move people in their own ways.',
            ],
        },
    },
    {
        slug: 'living-in-sound',
        title: 'Living In Sound',
        subtitle: 'The Music of Charles Mingus',
        year: '2022',
        label: 'Sunnyside Records',
        catalogNumber: 'SSC 1665',
        cover: '/images/albums/living-in-sound-md.jpg',
        tracks: [
            'Goodbye Pork Pie Hat', 'Peggy\'s Blue Skylight', 'Duke Ellington\'s Sound of Life',
            'Remember Rockefeller at Attica', 'Newcomer', 'Moves',
            'Sue\'s Changes', 'Invisible Lady', 'Underdog',
        ],
        musicians: [
            'Harry Skoler — Clarinet',
            'Kenny Barron — Piano',
            'Christian McBride — Bass',
            'Johnathan Blake — Drums',
            'Jazzmeia Horn — Vocals',
            'Nicholas Payton — Trumpet',
            'Megan Gould — Violin',
            'Tomoko Omura — Violin',
            'Karen Waltuch — Viola',
            'Noah Hoffeld — Cello',
        ],
        description: [],
        spotifyEmbed: 'https://open.spotify.com/embed/album/4TOAU5MmZwyYFVhJnKGI1h',
        bandcampUrl: 'https://harryskoler.bandcamp.com/album/living-in-sound-the-music-of-charles-mingus',
        artwork: 'Artwork by Dave Chisholm',
    },
    {
        slug: 'two-ones',
        title: 'Two Ones',
        year: '2009',
        label: 'Soliloquy Records',
        cover: '/images/albums/two-ones-md.jpg',
        tracks: [
            'Leaves of Autumn', 'Two as One', 'Alpine Sunset', 'Joyful Sorrow',
            'Giorgio\'s Theme', 'Piazzolla', 'Silent Serenity', 'Dad\'s Clarinet*',
            'Song for Jessy*', 'Life\'s Dreams', 'Two Onederful*', 'Jenna\'s Voice*',
            'Joyful Sorrow', 'Don\'t Say Words*', 'Hope',
        ],
        musicians: [
            'Harry Skoler — Clarinet',
            'Ed Saindon — Vibraphone and Piano',
            'Matt Marvuglio — Flute',
            'Barry Smith — Bass',
            'Bob Tamagni — Drums',
        ],
        description: [
            'Original jazz compositions featuring intimate portraits expressed through quintet and duo settings. The quintet\'s unique sound highlights the blend of clarinet, flute, and vibes/piano.',
        ],
        spotifyEmbed: 'https://open.spotify.com/embed/album/7kWJvopHUhGm6HnPzIwybO',
    },
    {
        slug: 'work-of-heart',
        title: 'A Work of Heart',
        year: '2000',
        label: 'Brownstone Recordings',
        cover: '/images/albums/a-work-of-heart-md.jpg',
        tracks: [
            'How Am I To Know', 'Coisa Feita', 'Don\'t Ever Go Away',
            'Portrait Of Daniel', 'Estate', 'Ev\'ry Time We Say Goodbye',
            'Your Story', 'Sophisticated Yenta', 'Soliloquy', 'Goodbye Mr. Evans',
        ],
        musicians: [
            'Harry Skoler — Clarinet',
            'Donn Trenner — Piano',
            'Garrison Fewell — Guitar',
            'Joe Lano — Guitar (tracks 2 & 8)',
            'Rich Margolis — Vibraphone (tracks 5 & 6)',
            'Roger Kimball — Bass',
            'Brace Phillips — Bass (tracks 2 & 8)',
            'Tim Gilmore — Drums',
            'John Abraham — Drums (tracks 2 & 8)',
            'Becky Ramsey — Violin',
            'DeAnn Burger — Violin',
            'Carol Jackson — Violin',
            'Ericka Syroid — Violin',
            'Carelene San Fillipo — Violin',
            'Frank Cropper — Violin',
            'Mary Trimble — Viola',
            'Barbara Gurley — Cello',
            'Beth Lano — French Horn',
        ],
        description: [
            'Charting in JAZZIZ Magazine\'s Top Ten "radioACTIVE" Jazz Chart for national radio airplay, this heartfelt, lush recording is a collection of diversified standard and original compositions that features jazz clarinet with six tracks augmented with Strings.',
        ],
        spotifyEmbed: 'https://open.spotify.com/embed/album/0FPfMzuI7FGzOtR5de6Z9U',
    },
    {
        slug: 'reflections',
        title: 'Reflections on the Art of Swing',
        subtitle: 'A Tribute to Benny Goodman',
        year: '1996',
        label: 'Brownstone Recordings',
        cover: '/images/albums/reflections-md.jpg',
        tracks: [
            'Topsy', 'Don\'t Be That Way', 'Undecided', 'Benny',
            'After You\'ve Gone', 'Rose Room', 'Handful of Keys', 'A Smooth One',
            'Jersey Bounce', 'What Can I Say After I Say I\'m Sorry',
            'Flying Home', 'Just You, Just Me', 'Reflections on the Art of Swing',
        ],
        musicians: [
            'Harry Skoler — Clarinet, Tenor Saxophone',
            'Ed Saindon — Vibraphone',
            'Roger Kimball — Bass',
            'Tim Gilmore — Drums',
        ],
        description: [
            'Reflections on the Art of Swing captures the timelessness of the songs associated with Benny Goodman, played in a unique quartet setting of Clarinet, Vibes, Bass and Drums that reflects a chamber-music approach with a fresh, distinctive, "voice".',
        ],
        spotifyEmbed: 'https://open.spotify.com/embed/album/2JSzixGVlP3F3KDjjlcnND',
    },
    {
        slug: 'conversations',
        title: 'Conversations in the Language of Jazz',
        year: '1995',
        label: 'Brownstone Recordings',
        cover: '/images/albums/conversations-md.jpg',
        tracks: [
            'If I Had You', 'Stompin\' at the Savoy', 'Memories of You',
            'The Shining Sea', 'Somewhere', 'Sweet Lorraine', 'The Sweetest Sounds',
            'Soon', 'I Wish I\'d Met You', 'Treasures', 'El Cajon',
            'Happiness Is', 'Recado Bossa Nova', 'Moonglow',
            'Conversations in the Language of Jazz',
        ],
        musicians: [
            'Harry Skoler — Clarinet, Tenor and Alto Saxophone, Flute, Piano',
            'Ed Saindon — Vibraphone, Piano',
            'Roger Kimball — Bass',
            'Tim Gilmore — Drums',
        ],
        description: [
            'Harry Skoler performs songs from the swing era, showcasing the music of Benny Goodman, Louis Armstrong, Duke Ellington and their contemporaries. The instrumentation\'s unique sound features clarinet and vibraphone in a quartet setting.',
            'The quartet\'s distinct arrangements are exacting and delicate. With an emphasis on a lyrical and fluid approach, the ensemble\'s style can best be described as \'chamber-like\'.',
        ],
        spotifyEmbed: 'https://open.spotify.com/embed/album/0He2PJ9ta34MbeoC9uBwp4',
    },
];

const album = computed(() => albums.find((a) => a.slug === props.slug));

const allAlbums = computed(() =>
    albums.map((a) => ({ slug: a.slug, title: a.title, cover: a.cover, year: a.year })),
);

const albumReviewCount = ref(0);
const albumRadioCount = ref(0);
const hasReviews = computed(() => albumReviewCount.value > 0);
const hasRadio = computed(() => albumRadioCount.value > 0);

async function fetchAlbumCounts() {
    try {
        const [reviewsRes, radioRes] = await Promise.all([
            fetch('/api/reviews'),
            fetch('/api/radio-airplays'),
        ]);
        const allReviews = await reviewsRes.json();
        const allRadio = await radioRes.json();
        albumReviewCount.value = allReviews.filter((r: any) => r.album_slug === props.slug).length;
        albumRadioCount.value = allRadio.filter((r: any) => r.album_slug === props.slug).length;
    } catch {
        // Silently fail
    }
}

const subNavLinks = computed(() => {
    if (!album.value) return [];
    const links = [
        { id: 'album-top', label: 'Overview' },
        { id: 'album-details', label: 'Details' },
    ];
    if (album.value.story) {
        links.push({ id: 'album-story', label: 'Story' });
    }
    if (hasReviews.value) {
        links.push({ id: 'album-reviews', label: 'Reviews' });
    }
    if (hasRadio.value) {
        links.push({ id: 'album-radio', label: 'Radio' });
    }
    links.push({ id: 'album-listen', label: 'Listen' });
    links.push({ id: 'album-more', label: 'More' });
    return links;
});

const activeSubSection = ref('album-top');
const mainNavHeight = ref(0);

function measureNav() {
    const nav = document.querySelector('.site-nav');
    if (nav) {
        mainNavHeight.value = nav.getBoundingClientRect().height;
        document.documentElement.style.setProperty('--nav-total-height', (mainNavHeight.value + 36) + 'px');
    }
}

function revealElements() {
    document.querySelectorAll('.reveal').forEach((el) => {
        if (el.getBoundingClientRect().top < window.innerHeight * 0.85) {
            el.classList.add('visible');
        }
    });
}

function onScroll() {
    measureNav();
    revealElements();
    const ids = subNavLinks.value.map((l) => l.id);
    for (let i = ids.length - 1; i >= 0; i--) {
        const el = document.getElementById(ids[i]);
        if (el && el.getBoundingClientRect().top <= 160) {
            activeSubSection.value = ids[i];
            break;
        }
    }
}

function scrollTo(id: string) {
    const el = document.getElementById(id);
    if (el) el.scrollIntoView({ behavior: 'smooth' });
}

const revealed = ref(false);
onMounted(() => {
    requestAnimationFrame(() => {
        revealed.value = true;
        measureNav();
        setTimeout(revealElements, 300);
    });
    window.addEventListener('scroll', onScroll, { passive: true });
    fetchAlbumCounts();
});

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
});
</script>

<template>
    <SiteLayout>
    <Head :title="`Harry Skoler — ${album?.title ?? 'Album'}`">
        <meta name="description" :content="`${album?.title ?? 'Album'} by Harry Skoler — ${album?.year ?? ''} ${album?.label ?? ''}. Jazz clarinet album featuring ${album?.musicians?.slice(0, 3).join(', ') ?? 'world-class musicians'}.`" head-key="description" />
        <meta property="og:title" :content="`Harry Skoler — ${album?.title ?? 'Album'}`" head-key="og:title" />
        <meta property="og:description" :content="`${album?.title ?? 'Album'} — a jazz clarinet album on ${album?.label ?? 'Red Brick Hill'}.`" head-key="og:description" />
        <meta property="og:image" :content="album?.cover" head-key="og:image" />
        <link rel="preload" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&family=Instrument+Serif:ital@0;1&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'" />
    </Head>

        <!-- Sub Nav (outside album-page to avoid opacity stacking context) -->
        <Teleport to="body">
            <nav v-if="album" class="album-subnav" :style="{ top: mainNavHeight + 'px' }">
                <a href="/#music" class="album-subnav-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    All Albums
                </a>
                <div class="album-subnav-sections">
                    <a
                        v-for="link in subNavLinks"
                        :key="link.id"
                        :href="`#${link.id}`"
                        :class="{ active: activeSubSection === link.id }"
                        @click.prevent="scrollTo(link.id)"
                    >{{ link.label }}</a>
                </div>
            </nav>
        </Teleport>

        <div v-if="album" class="album-page" :class="{ 'is-revealed': revealed }">

            <!-- Hero -->
            <header id="album-top" class="album-hero">
                <div class="album-hero-bg">
                    <img :src="album.cover" :alt="album.title" loading="eager" fetchpriority="high" />
                </div>
                <div class="album-hero-content">
                    <div class="album-hero-cover">
                        <img :src="album.cover" :alt="album.title" loading="eager" fetchpriority="high" />
                    </div>
                    <div class="album-hero-info">
                        <span class="album-label-tag">{{ album.label }}</span>
                        <h1 class="album-page-title">{{ album.title }}</h1>
                        <p v-if="album.subtitle" class="album-subtitle">{{ album.subtitle }}</p>
                        <div class="album-meta">
                            <span>{{ album.year }}</span>
                            <span v-if="album.catalogNumber" class="meta-sep">·</span>
                            <span v-if="album.catalogNumber">{{ album.catalogNumber }}</span>
                        </div>
                        <div v-if="album.bandcampUrl || album.appleMusicUrl" class="album-links">
                            <a v-if="album.bandcampUrl" :href="album.bandcampUrl" target="_blank" class="cta-btn">
                                Buy on Bandcamp
                            </a>
                            <a v-if="album.appleMusicUrl" :href="album.appleMusicUrl" target="_blank" class="cta-btn cta-btn-outline">
                                Apple Music
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Details Grid -->
            <section id="album-details" class="album-details-section">
                <div class="album-details-grid">
                    <div class="album-tracklist">
                        <h3 class="detail-heading">Track Listing</h3>
                        <ol class="track-list">
                            <li v-for="(track, i) in album.tracks" :key="i">
                                <span class="track-num">{{ String(i + 1).padStart(2, '0') }}</span>
                                <span class="track-name">{{ track }}</span>
                            </li>
                        </ol>
                    </div>
                    <div class="album-personnel">
                        <h3 class="detail-heading">Musicians</h3>
                        <ul class="musician-list">
                            <li v-for="(m, i) in album.musicians" :key="i">{{ m }}</li>
                        </ul>
                        <p v-if="album.artwork" class="artwork-credit">{{ album.artwork }}</p>
                    </div>
                </div>

                <div v-if="album.description.length" class="album-description">
                    <p v-for="(p, i) in album.description" :key="i">{{ p }}</p>
                </div>
            </section>

            <!-- Story -->
            <section v-if="album.story" id="album-story" class="album-story-section">
                <div class="story-inner">
                    <h2 v-if="album.story.heading" class="story-heading">{{ album.story.heading }}</h2>
                    <div v-if="album.story.heading" class="story-divider"></div>
                    <div v-if="album.story.paragraphs" class="story-body">
                        <p v-for="(p, i) in album.story.paragraphs" :key="i">{{ p }}</p>
                    </div>
                    <template v-if="album.story.sections">
                        <div v-for="(section, si) in album.story.sections" :key="si" class="story-section">
                            <h3 class="story-subheading">{{ section.subheading }}</h3>
                            <div class="story-body">
                                <p v-for="(p, pi) in section.paragraphs" :key="pi">{{ p }}</p>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <!-- Reviews -->
            <ReviewsSection
                v-if="hasReviews"
                :album-slug="album.slug"
                section-id="album-reviews"
                theme="section-dark"
            />

            <!-- Radio Airplay -->
            <RadioSection
                v-if="hasRadio"
                :album-slug="album.slug"
                section-id="album-radio"
                theme="section-dark"
            />

            <!-- Spotify Embed -->
            <section id="album-listen" class="album-listen-section">
                <h3 class="detail-heading listen-heading">Listen Now</h3>
                <div v-if="album.spotifyEmbed" class="spotify-wrap">
                    <iframe
                        :src="album.spotifyEmbed"
                        width="100%"
                        height="352"
                        frameBorder="0"
                        allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                        loading="lazy"
                        style="border-radius: 12px"
                    ></iframe>
                </div>
                <div v-else class="coming-soon">
                    <p class="coming-soon-date">May 1, 2026</p>
                    <p class="coming-soon-text">Coming Soon</p>
                </div>
            </section>

            <!-- Other Albums -->
            <section id="album-more" class="album-more-section">
                <h3 class="detail-heading more-heading">More Albums</h3>
                <div class="more-albums-grid">
                    <Link
                        v-for="a in allAlbums.filter((a) => a.slug !== album!.slug)"
                        :key="a.slug"
                        :href="`/album/${a.slug}`"
                        class="more-album-card"
                    >
                        <img :src="a.cover" :alt="a.title" loading="lazy" />
                        <div class="more-album-overlay">
                            <h4>{{ a.title }}</h4>
                            <span>{{ a.year }}</span>
                        </div>
                    </Link>
                </div>
            </section>

        </div>
    </SiteLayout>
</template>

<style scoped>
/* PAGE REVEAL */
.album-page {
    opacity: 0;
    transition: opacity 0.6s ease;
}
.album-page.is-revealed { opacity: 1; }

/* HERO */
.album-hero {
    position: relative;
    min-height: 60vh;
    display: flex;
    align-items: flex-end;
    overflow: hidden;
    padding-top: var(--nav-total-height, 92px);
}

.album-hero-bg {
    position: absolute;
    inset: 0;
    z-index: 0;
}

.album-hero-bg img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: blur(30px) brightness(0.45) saturate(0.7);
    transform: scale(1.2);
}

.album-hero-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(10, 10, 10, 0.4) 0%,
        rgba(10, 10, 10, 0.2) 40%,
        rgba(10, 10, 10, 0.75) 80%,
        var(--black) 100%
    );
}

.album-hero-content {
    position: relative;
    z-index: 1;
    display: flex;
    gap: 3.5rem;
    align-items: flex-end;
    max-width: 1200px;
    width: 100%;
    margin: 0 auto;
    padding: 4rem 3rem 4rem;
}

.album-hero-cover {
    flex-shrink: 0;
    width: 320px;
    aspect-ratio: 1;
    box-shadow:
        0 25px 60px rgba(0, 0, 0, 0.6),
        0 0 0 1px rgba(255, 255, 255, 0.05);
    overflow: hidden;
}

.album-hero-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.album-hero-info {
    padding-bottom: 1rem;
}

.album-label-tag {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: var(--red);
    display: block;
    margin-bottom: 1rem;
}

.album-page-title {
    font-family: 'AkzidenzGroteskPro', 'Playfair Display', serif;
    font-size: clamp(2.2rem, 4.5vw, 3.5rem);
    font-weight: 700;
    color: var(--white);
    line-height: 1.1;
    letter-spacing: -0.01em;
    margin-bottom: 0.4rem;
}

.album-subtitle {
    font-family: 'Instrument Serif', serif;
    font-style: italic;
    font-size: 1.3rem;
    color: var(--cream);
    opacity: 0.7;
    margin-bottom: 0.8rem;
}

.album-meta {
    font-size: 0.85rem;
    color: var(--cream);
    opacity: 0.5;
    letter-spacing: 0.06em;
    margin-bottom: 1.8rem;
}
.meta-sep { margin: 0 0.5rem; }

.album-links {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

/* DETAILS */
.album-details-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 5rem 3rem;
}

.album-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    margin-bottom: 3rem;
}

.detail-heading {
    font-family: 'AkzidenzGroteskPro', 'Playfair Display', serif;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--red);
    margin-bottom: 2rem;
    padding-bottom: 0.8rem;
    border-bottom: 1px solid rgba(184, 40, 46, 0.3);
}

.track-list {
    list-style: none;
    padding: 0;
    margin: 0;
    counter-reset: none;
}

.track-list li {
    display: flex;
    align-items: baseline;
    gap: 1rem;
    padding: 0.6rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    transition: color 0.3s;
}

.track-list li:hover { color: var(--white); }

.track-num {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.7rem;
    font-weight: 300;
    color: var(--cream);
    opacity: 0.35;
    min-width: 1.5rem;
    letter-spacing: 0.05em;
}

.track-name {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.92rem;
    font-weight: 400;
    color: var(--cream);
    letter-spacing: 0.01em;
}

.musician-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.musician-list li {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    color: var(--cream);
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}

.artwork-credit {
    margin-top: 2rem;
    font-size: 0.78rem;
    color: var(--cream);
    opacity: 0.4;
    font-style: italic;
    letter-spacing: 0.04em;
}

.album-description {
    max-width: 800px;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
}

.album-description p {
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    line-height: 1.8;
    color: var(--cream);
    opacity: 0.75;
    margin-bottom: 1.2rem;
}

/* STORY (Red Brick Hill) */
.album-story-section {
    background: var(--green-deeper);
    padding: 6rem 3rem;
}

.story-inner {
    max-width: 800px;
    margin: 0 auto;
}

.story-heading {
    font-family: 'AkzidenzGroteskPro', 'Playfair Display', serif;
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 700;
    color: var(--white);
    text-align: center;
    letter-spacing: -0.01em;
}

.story-divider {
    width: 60px;
    height: 2px;
    background: var(--red);
    margin: 1.5rem auto 3rem;
}

.story-section {
    margin-bottom: 3.5rem;
    padding-bottom: 3.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.story-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.story-subheading {
    font-family: 'AkzidenzGroteskPro', 'Playfair Display', serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--white);
    margin-bottom: 1.8rem;
    letter-spacing: 0.02em;
}

.story-body p {
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    line-height: 1.9;
    color: var(--cream);
    opacity: 0.8;
    margin-bottom: 1.5rem;
    text-align: justify;
}

/* LISTEN */
.album-listen-section {
    max-width: 900px;
    margin: 0 auto;
    padding: 5rem 3rem;
}

.listen-heading {
    text-align: center;
}

.spotify-wrap {
    border-radius: 12px;
    overflow: hidden;
}

.coming-soon {
    text-align: center;
    padding: 4rem 2rem;
    border: 1px solid rgba(184, 40, 46, 0.25);
    border-radius: 12px;
    background: rgba(184, 40, 46, 0.04);
}

.coming-soon-date {
    font-family: 'AkzidenzGroteskPro', 'Playfair Display', serif;
    font-size: clamp(1.8rem, 3vw, 2.6rem);
    font-weight: 700;
    color: var(--white);
    letter-spacing: -0.01em;
    margin-bottom: 0.5rem;
}

.coming-soon-text {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: var(--red);
}

/* MORE ALBUMS */
.album-more-section {
    padding: 5rem 3rem;
    max-width: 1200px;
    margin: 0 auto;
}

.more-heading {
    text-align: center;
}

.more-albums-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1.5rem;
}

.more-album-card {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1;
    text-decoration: none;
    display: block;
}

.more-album-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.8s cubic-bezier(0.22, 1, 0.36, 1), filter 0.5s;
}

.more-album-card:hover img {
    transform: scale(1.08);
    filter: brightness(0.5);
}

.more-album-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1.5rem 1rem;
    background: linear-gradient(to top, rgba(10, 10, 10, 0.95) 0%, transparent 100%);
    transform: translateY(30%);
    opacity: 0;
    transition: all 0.5s cubic-bezier(0.22, 1, 0.36, 1);
}

.more-album-card:hover .more-album-overlay {
    transform: translateY(0);
    opacity: 1;
}

.more-album-overlay h4 {
    font-family: 'AkzidenzGroteskPro', 'Playfair Display', serif;
    font-weight: 500;
    font-size: 0.85rem;
    color: var(--white);
    margin-bottom: 0.2rem;
    letter-spacing: 0.03em;
}

.more-album-overlay span {
    font-size: 0.7rem;
    color: var(--cream);
    opacity: 0.6;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .album-hero { min-height: auto; }
    .album-hero-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 5rem 1.5rem 3rem;
        gap: 2rem;
    }
    .album-hero-cover { width: 260px; }
    .album-hero-info { padding-bottom: 0; }
    .album-links { justify-content: center; }

    .album-details-section { padding: 3rem 1.5rem; }
    .album-details-grid {
        grid-template-columns: 1fr;
        gap: 3rem;
    }

    .album-story-section { padding: 4rem 1.5rem; }
    .album-listen-section { padding: 3rem 1.5rem; }

    .album-more-section { padding: 3rem 1.5rem; }
    .more-albums-grid { grid-template-columns: repeat(3, 1fr); gap: 1rem; }
}

@media (max-width: 600px) {
    .album-hero-cover { width: 200px; }
    .more-albums-grid { grid-template-columns: repeat(2, 1fr); }
    .album-page-title { font-size: 1.8rem; }
}
</style>

<style>
.album-subnav {
    position: fixed;
    top: 56px;
    right: 0;
    left: 0;
    z-index: 999;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 36px;
    padding: 0 3rem;
    background: rgba(10, 10, 10, 0.88);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}

.album-subnav-back {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--cream);
    opacity: 0.5;
    text-decoration: none;
    transition: opacity 0.3s, color 0.3s;
    white-space: nowrap;
}

.album-subnav-back:hover {
    opacity: 1;
    color: var(--white);
}

.album-subnav-back svg {
    transition: transform 0.3s ease;
}

.album-subnav-back:hover svg {
    transform: translateX(-2px);
}

.album-subnav-sections {
    display: flex;
    gap: 1.8rem;
    align-items: center;
}

.album-subnav-sections a {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--cream);
    opacity: 0.45;
    text-decoration: none;
    position: relative;
    padding-bottom: 2px;
    transition: opacity 0.3s, color 0.3s;
}

.album-subnav-sections a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0%;
    height: 1px;
    background: var(--red);
    transition: width 0.4s cubic-bezier(0.22, 1, 0.36, 1);
}

.album-subnav-sections a:hover {
    opacity: 1;
    color: var(--white);
}
.album-subnav-sections a:hover::after { width: 100%; }

.album-subnav-sections a.active {
    opacity: 1;
    color: var(--red-bright);
}
.album-subnav-sections a.active::after { width: 100%; }

@media (max-width: 900px) {
    .album-subnav { padding: 0 1.5rem; }
    .album-subnav-back { font-size: 0.6rem; }
    .album-subnav-sections { gap: 1.2rem; }
    .album-subnav-sections a { font-size: 0.6rem; letter-spacing: 0.08em; }
}

@media (max-width: 600px) {
    .album-subnav { display: none; }
}

#album-reviews.section {
    background: var(--green-deeper);
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}

#album-radio.section {
    background: var(--green-dark);
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}
</style>
