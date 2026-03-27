---
name: Never commit or push without explicit approval
description: Always wait for the user to explicitly ask before running git commit or git push
type: feedback
---

Never run `git commit` or `git push` (or any destructive/sharing git operation) without the user explicitly asking for it in that turn.

**Why:** The user said "chi ti ha detto di pushare e committare? Devi aspettarmi, SEMPRE!" — they were upset that I committed and pushed after fixing CI issues without being asked. Even when the task implies "fix and deploy", stop at the fix and wait for explicit approval.

**How to apply:** After making code changes, present the result and wait. Only commit/push when the user says something like "ok committa", "pusha", "commit and push", etc. in the current message.
