# Contributing

First of all, thanks for taking the time to contribute 🎉

## Issues and pull requests

- Not sure how to implement your contribution? [Open an issue](https://github.com/goedemiddag/laravel-autodiscovery-lock/issues/new) to discuss it with the community.
- If you haven't `rebase`d the recent changes in a while, make sure to do so
  - `git remote add upstream git@github.com:goedemiddag/laravel-autodiscovery-lock.git`
  - `git fetch upstream`
  - `git rebase upstream/main`
  - `git push origin main --force-with-lease`
- Early and unfinished pull requests are encouraged. This means the changes can be discussed and changes can be suggested where needed. Please make sure to mark the pull request as draft in that case. 
- 
- Make sure the tests pass once you mark your pull request as finished

## Commit messages and pull request titles

We follow the [Conventional Commit](https://www.conventionalcommits.org/en/v1.0.0/) specification to standardize our commit history.

The commit message summary (or pull request title) is constructed by prepending the type of change being made (e.g., feat, fix, refactor), followed by an imperative, present tense sentence (without a period). Example: `fix: make header bold`.
Optionally, a subject / scope may follow type in brackets. Example `feat(tests): add php mess detector`

### Pull request title

A good pull request title follows the same guidelines as commit messages.
