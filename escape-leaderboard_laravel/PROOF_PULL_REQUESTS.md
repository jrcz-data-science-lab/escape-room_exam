# Bewijs van gemaakte Pull Requests

Datum: 2026-01-13

Onderstaande PRs zijn door mij automatisch aangemaakt, gemerged en voorzien van labels/reviewers tijdens het examen-werk:

-   PR #6 — Fix: gebruik $casts property in User model

    -   Link: https://github.com/jrcz-data-science-lab/escape-room_exam/pull/6
    -   Branch: `fix/user-casts`
    -   Status: Gemerged

-   PR #7 — Fix: voeg dropColumn toe in migration down()

    -   Link: https://github.com/jrcz-data-science-lab/escape-room_exam/pull/7
    -   Branch: `fix/migration-down`
    -   Status: Gemerged

-   PR #8 — Test: voeg unit-tests toe voor User en Score modellen
    -   Link: https://github.com/jrcz-data-science-lab/escape-room_exam/pull/8
    -   Branch: `test/add-model-tests`
    -   Status: Gemerged

Lokale commits (relevante commits die bij de wijzigingen horen):

-   `7a96353` — Test: voeg unit-tests toe voor User en Score modellen
-   `22b9bc7` — Test: repareer ScoreModelTest (verwijder dubbele inhoud)
-   `4b8e591` — Test: re-save ScoreModelTest without BOM
-   `e4bfe27` — Fix: add dropColumn in migration down()
-   `dd9639c` — Fix: gebruik $casts property in User model

Hoe te verifiëren (lokale repo):

```bash
# Bekijk recente merge- en feature-commits
git log --pretty=format:"%h %ad %s" --date=iso -n 50

# Bekijk PR details in browser
gh pr view 6 --web
gh pr view 7 --web
gh pr view 8 --web
```

Opmerking: de PR-pagina's op GitHub zijn het sterkste bewijs (links hierboven). Dit bestand voegt een vaste, repo-gebaseerde referentie toe die je in je examen kunt tonen.
