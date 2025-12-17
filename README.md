# Minigry — how to publish to GitHub

Short steps to publish this project from your local machine (PowerShell / Windows).

1) Ensure `config.php` is NOT committed (we added `.gitignore` and `config.sample.php`). Copy the sample and edit locally:

```powershell
cp config.sample.php config.php
# edit config.php with your DB credentials
```

2) Initialize git (if not already):

```powershell
git init
git add .
git commit -m "Initial commit"
```

3) Create a GitHub repo and push:

Option A — with GitHub CLI (`gh`):

```powershell
gh repo create <USERNAME>/<REPO> --public --source=. --remote=origin --push
```

Option B — via website:

```powershell
# create repo on github.com, then:
git remote add origin https://github.com/<USERNAME>/<REPO>.git
git branch -M main
git push -u origin main
```

4) If you accidentally committed `config.php`, remove it from the index and push:

```powershell
git rm --cached config.php
git commit -m "Remove config.php"
git push origin main
```

To fully purge from history (advanced), use `git filter-branch` or the BFG Repo-Cleaner — be careful and read docs first.

That's it — your project will be on GitHub. If you want, I can run the git commands here or create the GitHub repo for you (I can show `gh` commands). 
