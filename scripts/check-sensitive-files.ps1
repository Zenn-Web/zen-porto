[CmdletBinding()]
param()

$ErrorActionPreference = 'Continue'

try {
    $gitOutput = @(& git ls-files --cached 2>&1)
    if ($LASTEXITCODE -ne 0) {
        throw ('git ls-files failed; run this guard from inside the repository. ' + ($gitOutput -join ' '))
    }
    $trackedPaths = @($gitOutput | Where-Object { $_ -is [string] })

    # .env and any .env.* variant, extensionless SSH private keys, and key/certificate stores.
    $sensitivePattern = '(^|/)(\.env(\..+)?|id_(rsa|dsa|ecdsa|ed25519)|.*\.(pem|key|p12|pfx|pkpass|jks|keystore))$'
    $allowedPattern = '(^|/)\.env\.example$'
    $sensitivePaths = @($trackedPaths | Where-Object { $_ -match $sensitivePattern -and $_ -notmatch $allowedPattern })

    if ($sensitivePaths.Count -gt 0) {
        [Console]::Error.WriteLine('Sensitive filenames are tracked: ' + ($sensitivePaths -join ', '))
        exit 1
    }

    $null = & git check-ignore --quiet -- .env 2>$null
    if ($LASTEXITCODE -ne 0) {
        [Console]::Error.WriteLine('.env is not ignored by Git.')
        exit 1
    }

    Write-Output 'Sensitive-file check passed: no tracked sensitive filenames; .env is ignored.'
    exit 0
}
catch {
    [Console]::Error.WriteLine($_)
    exit 1
}
