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

## 3. Coordinate Git history cleanup last

After credentials have been revoked or rotated, deployment values updated, and application health checked, assess whether sensitive material exists in Git history. Coordinate any history rewrite with repository owners and collaborators, identify all affected refs and clones, and prepare recovery and force-push plans. History cleanup is a separate repository operation; deleting a file in a new commit does not erase earlier revisions. Do not rewrite history or force-push without explicit approval and coordination. Rotating credentials and `APP_KEY` does not depend on a history rewrite being safe to perform immediately.

## Local prevention check

Run `powershell -ExecutionPolicy Bypass -File scripts/check-sensitive-files.ps1` from the repository root. It inspects Git-tracked path names only, fails if a sensitive filename is tracked or `.env` is not ignored, and does not read file contents. `.env.example` is allowed as a template; it must contain placeholders rather than real credentials.
