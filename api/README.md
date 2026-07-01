# jBrainRot Leaderboard API

Tiny PHP + MySQL backend for the global and daily leaderboards. The game works
fine without it (local scores only) — this is optional.

## Deploy (Hostinger shared hosting)

1. **Database** — hPanel → Databases → create a MySQL database + user, then run
   `schema.sql` in phpMyAdmin.
2. **Config** — copy `config.sample.php` to `config.php`, fill in the DB
   credentials and (optionally) change `JBR_SALT`. If you change the salt, also
   change `JBR_SALT` in `index.html`. Never commit `config.php`.
3. **Upload** — put `scores.php` and `config.php` somewhere public, e.g.
   `public_html/jbr/api/` → `https://yourdomain.com/jbr/api/scores.php`.
4. **Point the game at it** — in `index.html`, set
   `const API_URL = 'https://yourdomain.com/jbr/api/scores.php';`
   (empty string = online boards disabled).

## Endpoints

| Method | Query / body | Returns |
|--------|--------------|---------|
| GET | `?mode=global&limit=10` | top global scores `[{initials,score,wave,combo}]` |
| GET | `?mode=daily&day=YYYY-MM-DD&limit=10` | top scores for that daily |
| POST | `{initials, score, wave, combo, mode, day?, t}` | `{ok, rank}` |

## Anti-abuse (deterrence, not fortress)

- Plausibility caps: `wave ≤ 150`, `combo ≤ 99`, `score ≤ 3000 + wave×9000`
- Initials must match `^[A-Z]{1,3}$`; daily `day` must be UTC today ±1
- Shared-salt FNV-1a token (`t`) — matches `jbrToken()` in the client
- Rate limit: 20 posts per hashed IP per hour
- Honeypot: any POST with a non-empty `email` field is accepted and dropped
- CORS locked to `https://jgravelle.github.io`

## Testing with curl

```sh
# read the board
curl 'https://yourdomain.com/jbr/api/scores.php?mode=global&limit=10'

# a valid post needs a matching token; compute FNV-1a of "score|wave|day|SALT"
# (day is empty for global). See jbrToken() in index.html or scores.php.
curl -X POST 'https://yourdomain.com/jbr/api/scores.php' \
  -H 'Content-Type: application/json' \
  -d '{"initials":"JJG","score":4200,"wave":6,"combo":9,"mode":"global","t":"<token>"}'
```
