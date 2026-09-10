param (
    [Parameter(Mandatory=$true)]
    [string]$Command
)

[System.Reflection.Assembly]::LoadFrom("$HOME\ssh_net\lib\net462\Renci.SshNet.dll") | Out-Null

$hostIP = "173.212.197.126"
$user = "root"
$pass = "aBh4HperXWe2923hW3r1d6d3JOTl"

$ssh = New-Object Renci.SshNet.SshClient($hostIP, $user, $pass)
$ssh.ConnectionInfo.Timeout = [System.TimeSpan]::FromSeconds(60)
$ssh.Connect()

$cmd = $ssh.CreateCommand($Command)
$cmd.CommandTimeout = [System.TimeSpan]::FromSeconds(60)
$result = $cmd.Execute()

Write-Output $result
if ($cmd.Error) {
    Write-Host "STDERR: $($cmd.Error)" -ForegroundColor Yellow
}

$ssh.Disconnect()
$ssh.Dispose()
