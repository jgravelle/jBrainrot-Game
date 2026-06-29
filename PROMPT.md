# The one-shot prompt

This is the prompt that produced `index.html` in a single pass — no scaffolding
rounds, no re-feeding the codebase back into the model. Run it yourself and watch
the whole game appear at once.

---

## Build prompt

> Build a complete, playable browser game called "BRAINROT SHOOTER," a parody
> recreation of a viral meme first-person shooter. This is a single-pass build:
> produce the finished game in one go, no scaffolding rounds.
>
> **DESIGN INTENT (read first)**
> The game should LOOK cheap and janky on purpose, but FEEL genuinely good to play.
> Jank lives in the art (blocky, asymmetric, goofy), never in the controls or the
> gunplay. The target is "ugly but weirdly addictive": tight, responsive, juicy,
> and built around a one-more-run hook. If a choice trades visual polish for game
> feel, always pick game feel.
>
> **DELIVERABLE**
> - ONE self-contained file named index.html.
> - No build step, no npm, no bundler, no server. It must run by opening the file
>   directly in a browser (double-click).
> - Load Three.js from a CDN via a single script tag, pinned version:
>   `https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js`
> - Do NOT depend on external control addons (no PointerLockControls.js). Implement
>   first-person movement and mouse-look directly with the browser Pointer Lock API.
>   All game code lives inline in this one file.
>
> **VISUAL STYLE**
> - Minecraft-esque voxel look. Everything is made of cubes (BoxGeometry), flat
>   shading, bright saturated colors.
> - A flat ground plane built from a grid of grass-green cubes (roughly 40x40),
>   with a handful of scattered dirt/stone block clusters for cover.
> - Sky-blue background, simple ambient + directional light. No fancy shaders.
>
> **PLAYER AND CONTROLS**
> - First-person camera at human eye height.
> - Click the screen to lock the pointer and start. Show a centered start overlay
>   ("CLICK TO PLAY") that disappears on pointer lock and reappears on Esc.
> - WASD to move relative to look direction, mouse to look (yaw + clamped pitch).
> - Movement must feel snappy: quick acceleration, a little momentum, no floaty
>   drift. Add a Shift-to-sprint with a short cooldown so dodging feels good.
> - A simple weapon viewmodel: a small dark cube/box fixed to the bottom-right of
>   the view that recoils on fire and sways slightly with movement.
> - A crosshair (small white plus) fixed at screen center via an HTML overlay.
>
> **SHOOTING (make this feel great)**
> - Left-click fires. Raycast from the camera center forward.
> - Fast, responsive fire with a tight cooldown; ammo is infinite.
> - Sell every hit: a brief muzzle flash, a punchy synthesized "pew" (WebAudio,
>   wrapped in try/catch), a quick burst of colored cube particles at the hit
>   point, and a hitmarker flick on the crosshair.
> - On kill: enemy pops apart into its component cubes that scatter and fade, a
>   small "+score" number floats up, and a slightly beefier sound plays.
> - Add tiny hitstop (freeze the action for ~40ms) on each kill so impacts land.
> - Headshots (hitting the head cube) deal bonus score and a higher-pitched ding.
>
> **GAME FEEL AND THE HOOK**
> - Build a combo/streak system: chaining kills quickly raises a multiplier shown
>   on the HUD; missing or idling lets it decay. This is the core "one more run"
>   driver, so make the multiplier feel rewarding and a little greedy.
> - Rounds are short and snappy. Difficulty ramps smoothly: enemies get faster and
>   spawn thicker as score climbs, so tension builds toward an inevitable, funny,
>   overwhelmed death rather than a slow grind.
> - Constant readable feedback: screen-edge flash on damage, light camera shake
>   scaled to events, score and combo always glanceable.
> - Telegraph threats so deaths feel fair: enemies are slow but relentless, and the
>   danger comes from being surrounded, not from cheap shots.
> - Reward aggression: pushing into the crowd and chaining headshots should always
>   beat turtling in a corner.
>
> **ENEMIES (the brainrot)**
> - Blocky humanoid enemies: stack a few cubes into a simple body + head, each a
>   goofy bright color, slightly asymmetric so they look janky-on-purpose.
> - Give them personality through motion: an exaggerated waddle/bob, a little
>   lunge when close, a comedic stagger reaction when shot but not killed.
> - Above each enemy, float its name as a text label. Generate the label as a
>   canvas texture applied to a THREE.Sprite so it always faces the camera.
> - Pull names from this pool (cycle/randomize): "Skibidi Toilet",
>   "Tung Tung Tung Sahur", "Tralalero Tralala", "Bombardiro Crocodilo",
>   "Ballerina Cappuccina", "Chimpanzini Bananini", "Lirili Larila".
> - Enemies spawn at random points around the edge of the map and shamble toward
>   the player. If one reaches the player, it deals damage and despawns.
> - Endless waves: keep a healthy crowd alive at once; respawn as they die, and
>   ramp count and speed with score so it gets frantic.
>
> **HUD AND GAME LOOP**
> - Top-left: SCORE. Center-top: COMBO multiplier (animated when it climbs).
>   Top-right: HEALTH (start 100).
> - Taking enough hits drops health to 0 = GAME OVER overlay showing final score,
>   best combo, and "CLICK TO RESTART," which fully resets state. Surface a
>   high score kept for the session so players chase their own best.
> - Lightweight, readable HUD using HTML/CSS overlays over the canvas. Big chunky
>   monospace font.
> - Start-screen line: "Shoot the brainrot before it gets you."
>
> **POLISH (keep it cheap but juicy)**
> - Subtle idle bob on the viewmodel; recoil and sway on the gun.
> - Particles, hitstop, hitmarkers, and floating score numbers are the priority
>   effects; spend your polish budget there, not on the environment art.
> - Make sure it holds a smooth framerate with a thick crowd of enemies; keep
>   geometry simple and reuse materials.
>
> Constraints recap: one index.html, runs offline by double-click, Three.js r128
> from the CDN above, pointer lock implemented natively, enemy names as
> camera-facing sprite labels, and the feel (juice + combo hook) matters more than
> the graphics. Ship the whole thing in a single response.

---

## Follow-up tweak (one more pass)

A single small enhancement prompt after the first build:

> add a radar display in one corner so player (shown as white dot, centered) can
> see enemies 360 degrees (red dots), as well as ammo (yellow dots) and health
> (green dots); make enemies spawn more sparsely at first; don't want TOO intense
> right out of the box, but not boring either

That's the whole conversation. Two prompts, one finished game.
