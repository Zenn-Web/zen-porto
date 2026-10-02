# Secret Rotation and Git History Runbook

This provider-neutral runbook describes how to respond when a credential or application secret may have been exposed. Coordinate the work with the service owner and deployment operator. Do not put secret values in tickets, chat, source control, or command output.

## 1. Revoke and rotate provider credentials

1. Identify the affected provider credentials and the services that use them. Use the provider's trusted console or secret manager; do not copy values into this repository.
2. Revoke or rotate the affected credentials at the provider first. Revoke a credential immediately when exposure is active or abuse is suspected.
3. Update the deployment environment or secret manager with the replacement values. Keep the values out of source files and logs.
4. Restart or redeploy the affected services as required so they load the new values.
5. Check application health and verify the integrations work with the replacement credentials. Review provider activity for suspicious use and confirm the revoked credentials no longer work.

## 2. Rotate `APP_KEY` separately

Laravel's `APP_KEY` is distinct from provider credentials. Changing it can invalidate encrypted cookies and sessions and can make data encrypted with the old key unreadable. Inventory encrypted data and plan user/session impact before rotation. Back up required encrypted data and establish a recovery plan; do not assume old ciphertext can be decrypted with the new key. Generate and deploy the replacement through the approved secret-management process, then verify application health. Do not commit the key or place it in this runbook.

### Encrypted queued jobs

Queued contact mail (`App\Mail\ContactMessage`) implements `ShouldBeEncrypted`, so its payload in the `jobs` and `failed_jobs` tables is encrypted with the current `APP_KEY`. A worker running with a different key cannot decrypt those payloads and the jobs fail. Before rotating `APP_KEY`, choose one of these:

1. **Drain first (preferred):** put the site into maintenance mode (`php artisan down`, or the host's equivalent maintenance window) so no new contact submissions are queued. Workers pause while the application is down, so drain with `php artisan queue:work --force --stop-when-empty` until the `jobs` table is empty. Then either retry the entries in `failed_jobs` (`php artisan queue:retry all`) and wait until those retried jobs have finished (the `jobs` table is empty again and they did not land back in `failed_jobs`), or deliberately clear them (`php artisan queue:flush`). Only then rotate the key, deploy, run `php artisan queue:restart` so workers load it, and bring the site back with `php artisan up`.
2. **Keep the old key for decryption:** deploy the new key as `APP_KEY` and the old key in `APP_PREVIOUS_KEYS` (comma-separated), so pending and failed jobs, cookies and sessions encrypted with the old key can still be decrypted. Remove the old key from `APP_PREVIOUS_KEYS` only after the queue and `failed_jobs` no longer contain old payloads and sessions have expired. **If the old key may be compromised, prefer option 1**, or keep this window as short as possible: while the old key is listed, cookies and queue payloads forged with it are still accepted.

Either way, check `failed_jobs` and the log entry "Contact message delivery failed." after the rotation. Remember that a contact message lost this way was already acknowledged to the visitor as sent.

## 3. Coordinate Git history cleanup last

After credentials have been revoked or rotated, deployment values updated, and application health checked, assess whether sensitive material exists in Git history. Coordinate any history rewrite with repository owners and collaborators, identify all affected refs and clones, and prepare recovery and force-push plans. History cleanup is a separate repository operation; deleting a file in a new commit does not erase earlier revisions. Do not rewrite history or force-push without explicit approval and coordination. Rotating credentials and `APP_KEY` does not depend on a history rewrite being safe to perform immediately.

## Local prevention check

Run `powershell -ExecutionPolicy Bypass -File scripts/check-sensitive-files.ps1` from the repository root. It inspects Git-tracked path names only, fails if a sensitive filename is tracked or `.env` is not ignored, and does not read file contents. `.env.example` is allowed as a template; it must contain placeholders rather than real credentials.
