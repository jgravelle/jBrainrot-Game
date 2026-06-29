# jBrainrot-Game — BRAINROT SHOOTER

A janky-on-purpose, weirdly-addictive voxel first-person shooter. Parody of the
viral meme FPS — ugly Minecraft-esque cubes on the outside, tight and juicy
gunplay on the inside.

**It's one file.** No build step, no npm, no server. Just open `index.html` in a
browser (double-click) and start blasting.

[**▶ Play it**](https://jgravelle.github.io/jBrainrot-Game/)

---

## The Backstory

In June 2026, a San Francisco fintech startup called Slash told its whole company to go nuts with AI coding. One employee, Nick (head of strategic verticals, real name Nicolas Brillante), took that note very personally.

He sat down and built a video game. Not a product. Not a feature. A bare-bones, blocky, Minecraft-looking first-person shooter where you run around blasting characters named after viral brainrot memes: Skibidi Toilet, Tung Tung Tung Sahur, and the rest of the gang. He called it Brainrot Shooter, which, credit where it's due, is the correct name.

Then the bill showed up.

> **$81,267.** In one week. On the company card.

Slash handled it the only sane way: they posted the receipt to the internet and begged people to play the game so they could write it off as a marketing expense. The story went viral. And then the genuinely funny part happened: the dumb little game actually found an audience. Thousands of players, thousands of hours logged, all off the back of an accidental five-figure mistake.

Nick himself summed it up best, reposting a prediction-market account that picked up the story: "This is actually insane, am I going to become a case study for how AI spend can get out of control."

Yes, Nick. You are. Hi.

### Here's the part everyone got wrong

The internet's takeaway was "AI coding is a money pit." That's the lazy read, and it's wrong.

The robot didn't cost Nick eighty grand. Re-loading his entire codebase into the model's context, over and over and over, cost him eighty grand. Every time you ask an AI to "look at the whole project and change this one thing," you pay to make it re-read everything you already showed it five minutes ago. Do that for a full day of active development on a growing codebase and the meter spins like a slot machine that never pays out.

It is the single most common way people light money on fire with coding agents, and almost nobody notices until the invoice lands.

### So what is this repo?

This is Brainrot Shooter, rebuilt from scratch as a public service announcement.

Same dumb premise. Same blocky world. Same meme enemies waddling at you while a combo counter climbs and the whole thing slowly, hilariously overwhelms you. Janky on purpose in the art department, but tuned to actually be fun to play, because "ugly but weirdly addictive" is a legitimate genre and we respect it.

The difference is how it got built. One clean, complete prompt. One shot. No twelve-hour spiral of feeding the same files back into the model. And [jCodeMunch-MCP](https://jcodemunch.com) sitting in the middle the entire time, squeezing the junk out of the context before it ever reached the model, so the token bill stayed in the basement where it belongs.

> **Nick's bill: $81,267.**
> **My bill: $1.39.**
> Same brainrot. Different uncle.

The full one-shot prompt I used is included in this repo ([`PROMPT.md`](PROMPT.md)), so you can run it yourself and watch the whole game appear in a single pass. Open the result in a browser, no install, no server, no npm. Just double-click and start shooting.

It's free. It will always be free. Go play it.

### Credits and zero hard feelings

Genuine respect to Nick and to Slash for owning the mistake out loud instead of burying it. You turned an $81,267 oopsie into the best ad the coding-agent industry never paid for, and you handed the rest of us a perfect teachable moment on a silver platter.

If you want to never become the next case study, the lesson is simple: watch your context, not just your prompts. That's the whole game.

Now go protect your nuts.

*Built by J. Gravelle ("Weird Uncle J"). More token-efficiency tools at [jcodemunch.com](https://jcodemunch.com). More dumb videos at [youtube.com/@jjgravelle](https://youtube.com/@jjgravelle).*

---

## Controls

| Key | Action |
|-----|--------|
| **WASD** | Move |
| **Mouse** | Look |
| **Left-click** | Shoot |
| **Shift** | Sprint (short cooldown) |
| **R** | Reload |
| **Esc** | Pause |

Click the screen to lock the pointer and start.

## Features

- **First-person movement & mouse-look** built directly on the native Pointer
  Lock API (no control addons).
- **Juicy shooting** — muzzle flash, synthesized "pew", cube-particle hit bursts,
  hitmarkers, tiny hitstop on kills, and headshots for bonus score.
- **Combo / streak system** — chain kills fast to ramp a greedy multiplier; miss
  or idle and it decays. The core "one more run" hook.
- **The brainrot** — blocky, asymmetric, waddling meme enemies (Skibidi Toilet,
  Tung Tung Tung Sahur, Tralalero Tralala, Bombardiro Crocodilo, and friends)
  with camera-facing name labels.
- **Radar** — bottom-left minimap: you (white, centered), enemies (red), ammo
  (yellow), and health (green), 360° around you.
- **Pickups** — yellow ammo packs refill your mag, green health packs heal you.
- **Smooth difficulty ramp** — starts sparse, builds toward an inevitable, funny,
  overwhelmed death.

## Tech

- [Three.js r128](https://threejs.org/) from a CDN — the only dependency.
- Everything else is inline vanilla JS. WebAudio for sound, canvas textures for
  enemy labels and the radar.

## License

MIT
