[System.Reflection.Assembly]::LoadFrom("$HOME\ssh_net\lib\net462\Renci.SshNet.dll") | Out-Null

$hostIP = "173.212.197.126"
$user = "root"
$pass = "aBh4HperXWe2923hW3r1d6d3JOTl"

Write-Host "Connecting to $hostIP via SCP..." -ForegroundColor Cyan
$scp = New-Object Renci.SshNet.ScpClient($hostIP, $user, $pass)
$scp.Connect()

$localScript = [System.IO.FileInfo]"C:\xampp\htdocs\ettaba\deploy\setup_vps.sh"
Write-Host "Uploading setup_vps.sh to /tmp/setup_vps.sh..." -ForegroundColor Cyan
$scp.Upload($localScript, "/tmp/setup_vps.sh")
$scp.Disconnect()
$scp.Dispose()

Write-Host "Connecting via SSH to execute setup..." -ForegroundColor Cyan
$ssh = New-Object Renci.SshNet.SshClient($hostIP, $user, $pass)
$ssh.ConnectionInfo.Timeout = [System.TimeSpan]::FromMinutes(10)
$ssh.Connect()

$cmdStr = "sed -i 's/\r$//' /tmp/setup_vps.sh && chmod +x /tmp/setup_vps.sh && /tmp/setup_vps.sh"
$cmd = $ssh.CreateCommand($cmdStr)
$cmd.CommandTimeout = [System.TimeSpan]::FromMinutes(10)

Write-Host "Executing provisioning script on VPS..." -ForegroundColor Green
$asyncResult = $cmd.BeginExecute()

# Poll and print output in real-time
$reader = New-Object System.IO.StreamReader($cmd.OutputStream)
$errReader = New-Object System.IO.StreamReader($cmd.ExtendedOutputStream)

while (-not $asyncResult.IsCompleted) {
    while (-not $reader.EndOfStream) {
        $line = $reader.ReadLine()
        Write-Host $line
    }
    while (-not $errReader.EndOfStream) {
        $errLine = $errReader.ReadLine()
        Write-Host $errLine -ForegroundColor Yellow
    }
    Start-Sleep -Milliseconds 500
}

$cmd.EndExecute($asyncResult)

# Flush remaining
while (-not $reader.EndOfStream) {
    Write-Host ($reader.ReadLine())
}
while (-not $errReader.EndOfStream) {
    Write-Host ($errReader.ReadLine()) -ForegroundColor Yellow
}

Write-Host "Provisioning exit code: $($cmd.ExitStatus)" -ForegroundColor Cyan

# Fetch SSH Private Key for GitHub Actions
$keyCmd = $ssh.RunCommand("cat /root/.ssh/id_github_actions")
$privateKey = $keyCmd.Result

$ssh.Disconnect()
$ssh.Dispose()

Write-Host "`n=== GITHUB ACTIONS DEPLOY PRIVATE KEY ===" -ForegroundColor Magenta
Write-Host $privateKey -ForegroundColor Green
Write-Host "=========================================`n"

# Save private key to deploy directory (and ensure it's gitignored)
Set-Content -Path "C:\xampp\htdocs\ettaba\deploy\id_github_actions" -Value $privateKey
