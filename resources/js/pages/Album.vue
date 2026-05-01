<script setup lang="ts">
import SiteLayout from '@/layouts/SiteLayout.vue';
import ReviewsSection from '@/components/home/ReviewsSection.vue';
import RadioSection from '@/components/home/RadioSection.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { useIsMacDesktop } from '@/composables/useIsMacDesktop';

const props = defineProps<{ slug: string }>();
const { isMacDesktop } = useIsMacDesktop();

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
    labelLogo?: string;
    trackFootnote?: string;
    credits?: string[];
    copyright?: string;
    linerNotes?: { author: string; paragraphs: string[] };
    story?: { heading?: string; paragraphs?: string[]; sections?: { subheading: string; paragraphs: string[] }[] };
}

const albums: Album[] = [
    {
        slug: 'echoes',
        title: 'Echoes',
        year: '2026',
        label: 'Red Brick Hill',
        catalogNumber: 'RBH 2131',
        labelLogo: '/images/logos/red-brick-logo.png',
        cover: '/images/albums/echoes.png',
        tracks: [
            'Evocation*', 'Study in Orange (for Bill Evans)', 'JW, Michelangelo & the 40 Cent Burger (for James Williams)',
            'Everything\'s Cool, Everything\'s Cool! (for Rahsaan Roland Kirk)', 'Marian (for Marian McPartland)', 'Reminiscence*',
            'Thank You (for Teddy Wilson)', 'Waiting Patiently (for Benny Goodman)', 'Counterpart', 'Sea of Feeling (for Jimmy Giuffre)',
            'POW! (for Lionel Hampton)', 'Overtone', 'Never Played in Syracuse! (for Miles Davis)', 'Allusion*',
        ],
        musicians: [
            'Harry Skoler — Clarinet',
            'Bill Frisell — Guitar',
            'Dezron Douglas — Bass',
            'Johnathan Blake — Drums',
        ],
        description: [],
        spotifyEmbed: 'https://open.spotify.com/embed/album/7qoN7wGCOnfsho65HYKt9x',
        bandcampUrl: 'https://harryskoler1.bandcamp.com/album/echoes',
        appleMusicUrl: 'https://music.apple.com/us/album/echoes/1890977854',
        artwork: 'Illustration and layout by Julian Montague',
        trackFootnote: '* Compositions by Walter Smith III',
        credits: [
            'Produced by Walter Smith III',
            'Compositions by Harry Skoler and Walter Smith III*',
            'Harry Skoler compositions BMI | Walter Smith III compositions WS3 Music ASCAP',
            'Recorded at Sear Sound, NY, NY June 6 & 7, 2025',
            'Chris Allen — Recording Engineer',
            'Maximilian Troppe — Assistant Engineer',
            'Andrew Kochinka — Studio Assistant',
            'Roberta Findlay — Manager/Studio Owner',
            'Mixed and Mastered at The Mud Room by Chris Allen',
            'Harry Skoler plays Vandoren Reeds, Mouthpieces and Accessories, and Buffet Crampon Clarinets exclusively.',
        ],
        copyright: '© 2026 Red Brick Hill',
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
        labelLogo: '/images/logos/sunnyside-logo-white.png',
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
        labelLogo: '/images/logos/sunnyside-logo-white.png',
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
        appleMusicUrl: 'https://music.apple.com/us/album/living-in-sound-the-music-of-charles-mingus/1614535356',
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
        appleMusicUrl: 'https://music.apple.com/us/album/two-ones/318369952',
        linerNotes: {
            author: 'Ken Franckling, October 2008',
            paragraphs: [
                'The title of this recording project by clarinetist Harry Skoler invites several interpretations. It could reference the tracks "Two Onderful" or "Two as One." It could speak to the fact that this is a two-part recording, opening with seven pieces performed by jazz quintet, followed by eight more-intimate duos by Harry and longtime band-mate Ed Saindon, who is featured on piano rather than his better-known instrument, the vibes. But mostly, Two Ones is a project that celebrates the simpatico of Harry and Ed, who make very intuitive and personal music together. "It speaks to the combination of our individuality and teamwork as players," Harry says. "It is a high-wire act. You have to be in sync emotionally and in the phrasing."',
                'Harry and Ed began working together in 1993 and very quickly discovered the affinity that makes their collaborations so enjoyable. "I think we are coming from the same musical place," Saindon says.',
                'Two Ones is Harry\'s fourth recording as a leader, following Conversations in the Language of Jazz (1995), Reflections on the Art of Swing, a Tribute to Benny Goodman (1996) and A Work of Heart (1999). Three of the four projects have been collaborations with Ed.',
                'Both men are on the faculty at Boston\'s Berklee College of Music, and their fine band-mates here also have Berklee faculty connections: bassist Barry Smith is a longtime faculty member; flute player Matt Marvuglio, who was on the Berklee faculty when Harry was a student there in the 1970s, is Dean of the Performance Division; and drummer Bob Tamagni is an associate professor of percussion. All have extensive performing credentials. "They are very sharp players. Their quiet confidence made this project a joy," Harry says.',
                'At its core, this recording is rooted in emotion, both as inspiration for each composition \u2013 and their heart-felt, vibrant and highly improvised delivery. The project had a special spirit. "It was not a matter of making the music happen, but allowing it to emerge from you," Harry says. The late clarinetist, composer and bandleader Jimmy Giuffre, who was a teacher and mentor while Harry pursued his master\'s degree at New England Conservatory of Music in the 1980s, called that goal in music-making "stepping into a sea of feeling."',
                'Saindon wrote nine of the compositions and collaborated with Skoler on the other five. Most are ballads. Both writers say they were inspired by something real that touched them profoundly from everyday life. Nothing here was plucked out of the air \u2013 or a book of charts. It is helpful to know those inspirations in order to appreciate where the music is coming from \u2013 and where they take it.',
                'Ed wrote the seven quintet tunes. "Leaves of Autumn" enabled him to add a new melody and reharmonize the chord changes to "Autumn Leaves" in order to create a nice mood with impact and emotion. He penned the lovely "Two as One" for his wife, Pam, as an expression of emotion, humanity and life itself. This quintet version, featuring Ed on vibes, is a change from its first appearance on Key Play, his 2005 duo album with pianist Kenny Werner. "Alpine Sunset," Ed\'s first shift to piano on this project, was inspired by a train ride Ed and Pam took through the Alps during a trip to Switzerland last year. Through Harry and Ed\'s melodic interplay, you can hear the train climbing in elevation \u2013 and the breathtaking beauty of the moment.',
                '"Joyful Sorrow" is performed twice on this CD, here by the quintet and later by the clarinet-piano duo. The title may sound like an oxymoron, but Ed disagrees. "To me, a lot of music is filled with emotion, even conflicting emotion. I tried to capture both sides," he says. "You might hear it differently at different times, depending on who you are with." The duo version (track 13) is more reflective and wistful, and reveals more of Harry and Ed\'s uncanny and symmetrical unison playing. "Giorgio\'s Theme" was written for the father of Marco Pacassoni, one of Ed\'s former students who hails from Fano, Italy, a coastal city south of Venice. "I got to know Giorgio when I was doing a tour and some clinics. He and his family treated us royally," Ed says. The vibes-clarinet combination here conjures a pleasant ride through the Italian countryside.',
                'Ed wrote "Piazzolla" for Astor Piazzolla, whom he first heard when the "New Tango" master recorded with fellow-vibraphonist Gary Burton. He became fascinated with the Argentinean\'s sound and musical expression. "We\'re reaching for pure emotion and that\'s what Piazzolla was all about," he says. The final quintet track, "Silent Serenity," is intentionally somber to touch on the reflective side of human emotions. "I do a lot of writing in minor keys. They tend to have a lot of impact and are more powerful than major key tunes," Ed says. The tune also became a very nice showcase for Marvuglio\'s exquisite flute work. And what a joy to hear clarinet and flute playing in sync.',
                'Harry was inspired to write the first of the session\'s duo tunes, "Dad\'s Clarinet," for his father, Louis, who played classical clarinet from 1930 to 1941. He made an even more direct connection by playing it on his father\'s 1929 clarinet, which has a haunting, bittersweet tone. "This enabled me to focus on the bigger picture," Harry says. "It\'s about what this gentle, humble man has lived through \u2013 and his influence on my life."',
                '"Song for Jessy" was inspired by the daughter of close friends. Harry says the girl was upset about something that happened at school one day. He sat down at the piano, played "Somewhere Over the Rainbow" and snapped her out of her melancholy. He never forgot the impact that the music had. Ed wrote "Life\'s Dreams" because "everyone has dreams and that\'s what keeps us going. It applies to anyone\'s dreams," he says. "Two Onederful" was inspired by Amelia, one of Harry\'s two daughters. When she was young and recently adopted from China, he says she "didn\'t want two of anything. She wanted one in each hand \u2013 she called it \'two ones.\'"',
                'The inspiration for "Jenna\'s Voice" was simply hearing Jessy\'s sister\'s voice on the telephone one day. "The child had such a musical voice on the phone," Harry says. The duo version of "Joyful Sorrow" is followed by "Don\'t Say Words," which is dedicated to Harry\'s other daughter, Gianna, who also is adopted from China. Harry says Gianna changed the lyrics to "don\'t say words" when singing the lullaby "Mockingbird" ("Hush, little baby, don\'t say a word. Papa\'s gonna buy you a mockingbird.")',
                'This splendid session closes with Ed\'s tune, "Hope," which he calls a song of great emotion. "It is very positive and was written to leave the listener with a nice high," he says. It was a treat to hear so much of Saindon\'s piano playing on this CD, in addition to the pianistic approach that enriches his four-mallet technique on vibes.',
                '"There is a sense of something profound at work when we play together," Harry says. "I listen to the tracks and say, \'These things sound very well rehearsed.\' They weren\'t. We\'re all soloing all the time, even when one person is stretching out. It\'s all about the group sound."',
                'This project could have been called "Emotion" because that\'s what it is all about: How emotion and friendship enable like-minded musicians to create something greater, stronger and deeper than even they could imagine.',
            ],
        },
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
        appleMusicUrl: 'https://music.apple.com/us/album/a-work-of-heart/287182012',
        linerNotes: {
            author: 'Dr. Chuck Berg, University of Kansas',
            paragraphs: [
                'A Work of Heart! Yes, the title is a play on words. More profoundly, it\'s also an accurate summing-up of one of the most personal and musically appealing albums of recent years. Indeed, with the simpatico coupling of Harry Skoler\'s poignant clarinet and Donn Trenner\'s warm arrangements and pianistics, we\'re taken to realms rich with sonorous melodic and harmonic pleasures.',
                'Skoler, although a first-rate saxophonist and flutist, has made his major impact on clarinet thanks to Conversations in the Language of Jazz (1995) and Reflections on the Art of Swing: A Tribute to Benny Goodman (1996), both also on Brownstone. Along with stalwarts such as Eddie Daniels, Ken Peplowski and Gary Foster, Skoler has helped rekindle interest in the clarinet as a uniquely expressive contemporary "voice." Here, Skoler\'s impassioned playing, while reflecting the legacy of legends such as Benny Goodman and Buddy DeFranco, clearly stands on its own. It\'s an impression redoubled due to the clarinetist\'s highly personal compositions, co-crafted with Trenner.',
                'Donn Trenner is a name more of us should be familiar with. As a bop pianist, his credits include Charlie Parker, Stan Getz, Oscar Pettiford, Dave Pell, Howard McGhee and Ben Webster. As for big bands, he\'s held down the piano chair with Charlie Barnet, Tommy Dorsey and Les Brown; with Brown, he was Bob Hope\'s major musical foil. As a music director-pianist-arranger, he\'s lifted bandstands with Lena Horne, Nancy Wilson, Jack Jones, Dick Haymes and Ann-Margret, and for TV\'s Steve Allen. He\'s also responsible for the sumptuous charts for another landmark Brownstone release, Here\'s to Joe (1996), spotlighting the gifted vocalist Paul Broadnax.',
                'In assembling these notes, I had the pleasure of speaking with Harry and Donn, two thoughtful gentlemen who in separate phone conversations spoke warmly of what obviously has become a mutual admiration society. Interestingly, it was the Broadnax project that first brought them together. Before that, however, there was a Wes Montgomery album. "When I was a kid," Harry recalled, "there was a double album by Wes Montgomery with string arrangements of tunes like \'Here\'s That Rainy Day.\' I must have worn that album out. That\'s how much I loved it. So I can trace the desire to play with strings back to that double album."',
                'That desire was refired in February 1997 when Harry caught Paul Broadnax at Sculler\'s in Boston. "I hadn\'t heard the record, but I was curious. That\'s where I first heard Donn. They didn\'t have live strings. Instead, he had synthesizers playing the string parts. Still, it was a beautiful sound. I introduced myself to Donn and before leaving the club, bought the CD. It moved me so much that when I got home, I knew that it was time for the string project, and that Donn was the person I wanted to work with. That night I decided to write him."',
                'Donn picks up the story. "I got this lovely letter which was so sincere. So I called and told him to come and visit. After talking about various possibilities, he asked me if I would undertake the project. I said \'sure.\' The first thing I wanted to do was to play with his guys. I wanted to sit down and see what it felt like. They were sensitive players, and good to work with. They were also gentlemen, which is a high priority with me."',
                'The well-balanced repertory consists of mostly off-beat standards and engaging originals. "A lot of the pieces by other artists are tunes that helped get me through the years, the bad times as well as the good times," says Harry. "They\'re the kinds of tunes that I would listen to all night long. Eventually, each one became so much a part of me that I thought, \'I\'ve got to play that piece.\' The feelings that went into this music, the originals as well as the standards, run the emotional gamut. For me, music has been a saving grace. It\'s been the means of expressing the deepest parts of myself. And, it\'s always come from a place of love."',
                'The process of developing the originals speaks to an unusually high level of collaboration. Initially written by Harry, the tunes were then revised under Donn\'s experienced eye. "When we got to Harry\'s tunes," Donn recalls, "we were FAXing back and forth. There was an emotional statement that Harry was making that was important to understand and include and, I hoped, maybe help make more cohesive. He was wonderful to work with, very open." Harry was also grateful to the unusual and unselfish extent of having Donn listed as co-composer.',
                'Harry recalls writing "Goodbye, Mr. Evans" in 1980 to commemorate the passing of the great pianist. "Sometimes I\'ve written tunes that come together over a period of time, where I go back and re-write. This, though, is one of those pieces that I wrote from beginning to end, even though I\'ve made a few changes from time to time. I didn\'t consciously write it in 3/4 because Bill was associated with waltzes. It just happened. At any rate, I played it many times through the years, including my graduate recital under Jimmy Giuffre."',
                '"I don\'t want to sound like an egomaniac, but it\'s one of those pieces that I love. When I showed it to Donn, he liked it and revised the piece once again. He helped make the tune stronger, and yet it never lost the essence of being my composition. That\'s one of the magical things about working with Donn. When he speaks, he looks you right in the eye. He has the same approach to your writing. Also, he\'ll sit with it for the longest period of time. Even after he feels it\'s done, he\'ll sometimes come back and say, \'I\'m still unsettled about this part here.\' So we kept the FAX and phone lines pretty busy. He\'d listen to my suggestions in a way that felt like he was an extension of my writing. He was so respectful of the music that by the time a tune was finished, it wasn\'t drastically different from the original. Still, it was those nuances that made it a true collaboration. It was a great experience."',
                'The logistics of the recording process constitute another saga. Indeed, it was Donn\'s teaming with Paul Broadnax that formed the template. "Harry was enamored with Paul\'s project. He liked the way it turned out. So, after we did the initial tracks in Boston with Harry, I went to Las Vegas to write and record the arrangements with a string section that I had worked with through the years. The Las Vegas players, who can play anything, have a real bent for jazz. They don\'t \'pooh pooh\' it like so many other string players."',
                '"I also had the help of Joe Lano, a great guitarist who also writes and arranges, and who\'s responsible for the chart on Garry Dial\'s \'Sophisticated Yenta.\' A lot of the orchestration for strings came directly out of what I played on piano. We also used a French horn when it fit. So by writing in a manner that called for three passes, we were able to go into the studio and create the feeling of 24 strings and 3 French horns. I believe in simplicity. If you can color something gently and not overload it with everything available, you have a good chance of being successful. That\'s really how I approach everything. Also I wanted to leave Harry loose at times so you could feel him without the lushness. That\'s how I tried to balance it out."',
                'In my conversation with Harry, "trust" was a word that came up time and again. Indeed, Harry\'s faith in Donn\'s multiple talents was clearly well placed. For me, Harry\'s lyrical playing and Donn\'s gently caressing writing (and keyboard work) are flip sides of the same stylistic coin . . . clear, clean and lean, and yet also lush in an attractively understated way. There\'s a spatial subtlety, a kind of sublime reciprocity derived as much from knowing what to omit, as what to play (and write). As a result, the music breathes. It\'s alive.',
                'In this uncommon collaboration, I would be remiss not to mention the vital contributions of Harry\'s and Donn\'s colleagues. The players from Boston and Las Vegas are superb. And yes, Donn is right, the strings do play with a jazz-nuanced hipness that\'s on the money. I\'m also taken with the two guitarists, Garrison Fewell and Joe Lano, both consummate pros. Also noteworthy is the rhythm section work which motors with Rolls-Royce aplomb.',
                'Harry is wonderful. His gorgeous sound shimmers, bringing everything to immediate and fully realized life. And, Donn? Along with his exquisite charts, we\'re treated to the tasteful yet in-the-moment pianistics that previously helped heat sessions with the likes of Parker and Getz.',
                'One could spin words galore about the individual tracks, but they, I think, more than stand on their own. Therefore, let me conclude by referencing the album\'s title, A Work of Heart. In fact, it is the heart of Harry Skoler that is at centerstage. Like great artists of every genre, Harry connects to us by daring to reveal his deepest experiences, his moments of pain as well as ecstasy, indeed, his soul. That willingness, perhaps, compulsion, to share is a risky bit of artistic business. Harry, however, prevails.',
                'It is music of transcendent beauty, emotionally direct and yet also timeless.',
                'To conclude, I\'d like go out on a limb by predicting that this extraordinary collaboration by Harry Skoler and Donn Trenner promises to stand next to Stan Getz\'s nonpareil teaming with Eddie Sauter (Focus; 1961), as one of the benchmarks of jazz cum strings.',
            ],
        },
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
        appleMusicUrl: 'https://music.apple.com/us/album/reflections-on-the-art-of-swing/287879117',
        linerNotes: {
            author: 'Scott Yanow',
            paragraphs: [
                'Harry Skoler\'s debut for Brownstone (Conversations in the Language of Jazz) featured his quartet playing swing standards along with some more modern pieces and the leader\'s original "Treasures." For his second recording, the clarinetist looks back towards his original inspiration, Benny Goodman.',
                'Skoler\'s father had been a clarinetist but quit years before his son was born, and his mother was a singer who also played piano. "My mother started giving me piano lessons when I was five but I was not that interested so it didn\'t last very long" remembers Skoler who was born and raised in Syracuse, New York. "I took up clarinet when I was nine and had such miserable teachers that I quit within nine months. Then when I was thirteen my parents found a great teacher, Doug Soyars, and I started taking lessons from him. After a year and a half, he asked if I wanted to learn about jazz. I didn\'t know anything about jazz at that point so he gave me a clarinet transcription of Benny Goodman from the 1940\'s and told me to go home and learn it. I did and the following week played it for him, horribly, as if it were a classical exercise. He took the clarinet out of my hand and wailed on the piece. The sound of the clarinet along with the swing rhythm completely blew me away and I went out and bought the album Benny Goodman\'s Greatest Hits. I made up my mind that this was going to be what I did with my life and I told my parents that night that I was going to be a jazz clarinetist."',
                'Skoler knew what direction he wanted to go in fairly early. "I started collecting records and my parents took me to concerts featuring such great musicians as Earl Hines, Bobby Hackett, Lionel Hampton, Teddy Wilson and Goodman. I was willing to play anywhere and had all kinds of gigs, getting all of the experience I could." He attended Ithaca College for a period and then went to Berklee College of Music (where two of his favorite teachers were Joe Viola and Andy McGhee), graduating in 1978. By then Skoler was also playing tenor, flute and piano and was quite familiar with more modern styles of jazz. A three year period in Nashville found him gigging with his own group, teaching and working in the studios. "I became concerned about making enough money so I went into architecture since my father is an architect. I studied at Syracuse University, but I just wasn\'t happy being away from playing. So I returned to music, attended the New England Conservatory and studied with Jimmy Giuffre. I found the confidence to fully dedicate myself to music; it has worked out well."',
                'Since graduating from the New England Conservatory in 1986 with a Master of Music degree in Jazz Studies, Harry Skoler has been quite busy. He has worked in the studios, played with the shows "Ain\'t Misbehavin\'" and "Nunsense" and led his own groups, most notably the quartet featured on both of his Brownstone recordings.',
                '"Tim Gilmore has been playing drums with me since 1984. We worked together in a number of groups including a trio where I was playing mostly piano and synthesizer. Tim and I really connect and seem to be able to read each other\'s minds. I have been playing with bassist Roger Kimball since 1987 and I also originally played piano with him. In both cases our friendships have made our exchanges more conversational than with other players. I played a trio concert in 1993 with Tim and Roger (the first time that we all performed together) and at that point I wanted to get back to playing clarinet. I put on a couple of additional concerts with a pianist to round out the quartet but it wasn\'t clicking. Ed Saindon was the missing piece. Roger had played with Ed before and he recommended him. The first time we got together at Ed\'s house, it worked. Ed plays vibes quite pianistically and has a unique style with sensitive phrasing and lots of color and texture. The quartet provides the perfect setting for my clarinet."',
                'While Harry Skoler had played tenor, alto, flute and piano on one selection apiece (in addition to his clarinet) on Conversations in the Language of Jazz, for his Benny Goodman tribute he sticks exclusively to clarinet except for a taste of his cool-toned tenor on "Undecided."',
                '"Ed Saindon did the arrangements, and the whole quartet had input during the rehearsals" remembers Skoler. "We played some of the first tunes that I had loved when I was a kid. I did not try to sound like Benny Goodman but I hear him in the songs. I feel that I can be myself playing swing and can make my own contribution without having to try to sound like a Benny Goodman clone." Although some of the arrangements closely recall the Benny Goodman Quartet and Sextet of the 1935\u201345 period, Skoler has his own lyrical sound on clarinet and does not copy Goodman\'s solos. Also, Ed Saindon\'s style owes as much to Red Norvo as to Lionel Hampton and the lack of a piano in the rhythm section helps give the Quartet its own personality.',
                'Each of the 11 swing standards are easily enjoyable without detailed program notes. A few of the highlights include "Don\'t Be That Way" (which Goodman usually performed with his big band rather than small group), the uptempo "After You\'ve Gone," Fats Waller\'s tricky "Handful Of Keys," a charming rendition of "What Can I Say After I Say I\'m Sorry" and an unusual version of "Just You, Just Me" that is a clarinet-vibes duet. In addition there are two new and original compositions. "Benny" is a thoughtful duet by its co-composers Skoler and Saindon, while the vibraphonist contributed the complex but swinging "Reflections on the Art of Swing."',
                'In addition to playing regularly with his Quartet, Harry Skoler performs at schools throughout New England with his "Adventures With Jazz" program. "We have a whole show that involves the kids and gets them to improvise for the first time. It is a mission I have to introduce this music to younger people." Through his own example and rewarding recordings such as Reflections on the Art of Swing, Harry Skoler is insuring that swing music will continue to thrive for decades to come.',
            ],
        },
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
        appleMusicUrl: 'https://music.apple.com/us/album/conversations-in-the-language-of-jazz/289144282',
        linerNotes: {
            author: 'Ken Franckling',
            paragraphs: [
                'Harry Skoler, born in Syracuse, N.Y in 1956, first heard Benny Goodman\'s solos played by his teacher Douglas Soyars during a clarinet lesson in 1971. In Professor Soyar\'s attempt to introduce Harry to the language of jazz, an old transcription of Goodman\'s solo from the tune "Grand Slam" was assigned for study. Mostly to appease Soyars, the transcription was practiced, and played at the next lesson in the style of a classical piece (the only style Harry was familiar with). Soyars proceeded to take the clarinet from Harry\'s hands, and wailed through the transcribed solo like a hot knife through butter. Ears singed from this spiritual awakening, Harry ran to pick up his first Goodman record. After repeated listenings, he told his family that evening that he aspired to be a jazz clarinetist.',
                'Twenty four years have passed since that day in the small basement studio where those first licks introduced Harry to the language of jazz. Schools were attended, careers changed, cities moved to and from, and the addition of saxophone, flute, and piano to his arsenal of voices. However, the voice of the clarinet is still the deepest voice in his heart.',
                'Harry graduated from Berklee College of Music in 1978, having studied with Joe Viola, Andy McGhee, and Greg Badolato. During a three year period in Nashville, Tennessee, he performed with his jazz groups, taught privately, and played in the studio for the first time. In 1982, he became intrigued with architecture, and decided to study at Syracuse University. Although studying architecture proved interesting and challenging, Harry began to escape from the late night design sessions and participate in local jam sessions. A year later, music was once again the sole pursuit of his life, and with renewed commitment to becoming a jazz musician, he enrolled at New England Conservatory. Here he studied clarinet, saxophone, flute, and composition with the legendary Jimmy Giuffre, and began performing with jazz groups throughout New England.',
                'Harry graduated in 1986 with a Master of Music degree in Jazz Studies. In addition to his quartet, he performs with Adventures With Jazz, educating young people in the language of jazz throughout New England. He has performed with the casts of "Ain\'t Misbehavin\'" and "Nunsense," and has recorded for television and numerous other projects.',
                'His jazz quartet illuminates Harry\'s passion for the clarinet, "conversing" with vibraphone, bass and drums. The quartet additionally features Harry on saxophone, flute, and piano, and performs both original music as well as music from the Great American Songbook, and of course . . . the music of Benny Goodman (played and arranged with the quartet\'s distinct approach).',
                'The clarinet tends to be more a footnote than a specialty for most players of reed instruments on today\'s jazz scene. Harry Skoler understands its endangered species status in jazz and in his own expressive way is working to reverse it. "The clarinet is a very difficult instrument to stick with in a group," he explains. "People can step all over it."',
                'That doesn\'t happen in Skoler\'s New England based quartet, where the clarinet is his axe of preference though he sometimes doubles on the saxophones, flute and piano. While building his group and finding his sound over the past half dozen years, a special affinity developed between Skoler\'s clarinetics and the vibraphone playing of Boston jazz veteran Ed Saindon.',
                'Like the flip sides of a coin, like yin and yang, these two instruments were made to be played together. While they often share space in larger musical ensembles, rarely have clarinet and vibes been showcased with the empathy heard on this session, including three very fine duets (Memories Of You, I Wish I\'d Met You, and Moonglow).',
                'With no short shrift intended for bassist Roger Kimball and drummer Tim Gilmore, who are solid equal partners on this session, the clarinet-vibes pairing grabs the ear because of the special musical combination that is at work. "I noticed that right off the bat when playing with Ed. I\'ve been able to come up with ideas I have never found with anybody else," Skoler says. "We\'re not always sure who is following whom when we play, but there is always a real melodic affinity."',
                'The music on Skoler\'s first recording as a leader swings sweetly. At times it is exacting and delicate. There\'s a cool jazz feel that avoids the roughshod abandon of beboppers even when Harry\'s playing breaks into an occasional hot sweat.',
                'The standards Skoler & Co. offer up here are melodic showcases. Clarinet and vibes are featured from the start on Irving Berlin\'s classic If I Had You, Skoler\'s hero Benny Goodman\'s Stompin\' At The Savoy, the three aforementioned duets and several other gems, including Sweet Lorraine, the Bernstein/Sondheim classic Somewhere from West Side Story, and the Gershwins\' Soon. We are treated to a duet shift on the Brazilian standard Recado Bossa Nova, which features Skoler\'s clarinet with Kimball\'s bass work.',
                'Harry shifts to tenor sax, and Ed Saindon to piano, for the classic Johnny Mandel/Peggy Lee tune The Shining Sea, which floats easily over a mood setting Latin beat from Kimball and Gilmore. We get a taste of Harry\'s alto playing on Treasures, an original written for his fianc\u00E9e, Joanne; his flute work on The Sweetest Sounds; and his piano on Happiness Is, a tasty ballad written by melody master Vince Guaraldi.',
                'The level of empathy among these players comes through clearest on a lesser-known but equally exquisite Johnny Mandel treasure, El Cajon, and on the finale. The latter is an extended solo showcase written by Ed Saindon that has been titled Conversations in the Language of Jazz. Conversation is the right word. Listen to the interplay within the quartet as Harry gradually works his way up the clarinet\'s three registers while stretching the melody with his solos.',
                'Harry Skoler has learned his lessons well, particularly mentor Jimmy Giuffre\'s advice to trust his individuality, to play with clarity, and always use his music to tell a story.',
                'That advice is the essence of these fine conversations.',
            ],
        },
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
    if (album.value.linerNotes) {
        links.push({ id: 'album-liner-notes', label: 'Liner Notes' });
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
                                <svg class="bandcamp-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M0 18.75l7.437-13.5H24l-7.438 13.5H0z"/></svg>
                                {{ album.spotifyEmbed ? 'Buy on Bandcamp' : 'Buy on Bandcamp' }}
                            </a>
                            <a v-if="album.appleMusicUrl" :href="album.appleMusicUrl" target="_blank" class="cta-btn cta-btn-outline">
                                <svg class="apple-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                                Apple Music
                            </a>
                        </div>
                        <p v-if="album.appleMusicUrl && isMacDesktop" class="apple-music-note">Mac users: opens in Music app. To purchase, select iTunes Store inside Music.</p>
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
                        <p v-if="album.trackFootnote" class="track-footnote">{{ album.trackFootnote }}</p>
                    </div>
                    <div class="album-personnel">
                        <h3 class="detail-heading">Musicians</h3>
                        <ul class="musician-list">
                            <li v-for="(m, i) in album.musicians" :key="i">{{ m }}</li>
                        </ul>
                        <div v-if="album.labelLogo && album.catalogNumber" class="label-badge">
                            <img :src="album.labelLogo" :alt="album.label" class="label-logo" />
                            <span class="label-catalog">{{ album.catalogNumber }}</span>
                        </div>
                        <p v-if="album.copyright" class="copyright-notice">{{ album.copyright }}</p>
                        <p v-if="album.artwork" class="artwork-credit">{{ album.artwork }}</p>
                    </div>
                </div>

                <div v-if="album.credits?.length" class="album-credits">
                    <h3 class="detail-heading">Credits</h3>
                    <ul class="credits-list">
                        <li v-for="(c, i) in album.credits" :key="i">{{ c }}</li>
                    </ul>
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

            <!-- Liner Notes -->
            <section v-if="album.linerNotes" id="album-liner-notes" class="album-liner-notes-section">
                <div class="liner-notes-inner">
                    <h2 class="story-heading">Liner Notes</h2>
                    <div class="story-divider"></div>
                    <div class="story-body">
                        <p v-for="(p, i) in album.linerNotes.paragraphs" :key="i">{{ p }}</p>
                    </div>
                    <p v-if="album.linerNotes.author" class="liner-notes-author">— {{ album.linerNotes.author }}</p>
                </div>
            </section>

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
                    <p class="coming-soon-text">Available Now!</p>
                    <div v-if="album.bandcampUrl || album.appleMusicUrl" class="coming-soon-links">
                        <a v-if="album.bandcampUrl" :href="album.bandcampUrl" target="_blank" class="cta-btn"><svg class="bandcamp-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M0 18.75l7.437-13.5H24l-7.438 13.5H0z"/></svg> Buy on Bandcamp</a>
                        <a v-if="album.appleMusicUrl" :href="album.appleMusicUrl" target="_blank" class="cta-btn cta-btn-outline"><svg class="apple-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg> Apple Music</a>
                    </div>
                    <p v-if="album.appleMusicUrl && isMacDesktop" class="apple-music-note" style="margin-top:1rem;">Mac users: opens in Music app. To purchase, select iTunes Store inside Music.</p>
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

[id^="album-"] {
    scroll-margin-top: var(--nav-total-height, 100px);
}

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

.apple-music-note {
    margin-top: 0.6rem;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.72rem;
    color: var(--cream);
    opacity: 0.45;
    font-style: italic;
    letter-spacing: 0.02em;
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

.track-footnote {
    margin-top: 1.2rem;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.78rem;
    color: var(--cream);
    opacity: 0.5;
    font-style: italic;
    letter-spacing: 0.03em;
}

.label-badge {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 2rem;
}

.label-logo {
    width: 36px;
    height: auto;
    opacity: 0.7;
}

.label-catalog {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    color: var(--cream);
    opacity: 0.55;
    letter-spacing: 0.06em;
}

.copyright-notice {
    margin-top: 1rem;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.75rem;
    color: var(--cream);
    opacity: 0.45;
    letter-spacing: 0.04em;
}

.artwork-credit {
    margin-top: 2rem;
    font-size: 0.78rem;
    color: var(--cream);
    opacity: 0.4;
    font-style: italic;
    letter-spacing: 0.04em;
}

.album-credits {
    margin-top: 1rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
}

.credits-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.credits-list li {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem;
    color: var(--cream);
    opacity: 0.7;
    padding: 0.4rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    letter-spacing: 0.01em;
    line-height: 1.6;
}

.credits-list li:last-child {
    border-bottom: none;
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

/* LINER NOTES */
.album-liner-notes-section {
    padding: 6rem 3rem;
    background: var(--green-deeper);
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}

.liner-notes-inner {
    max-width: 800px;
    margin: 0 auto;
}

.liner-notes-author {
    margin-top: 2.5rem;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    color: var(--cream);
    opacity: 0.6;
    font-style: italic;
    text-align: right;
    letter-spacing: 0.02em;
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

.coming-soon-links {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
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
    .album-liner-notes-section { padding: 3rem 1.5rem; }
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
