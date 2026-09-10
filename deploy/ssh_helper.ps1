# Load SSH.NET assembly
[System.Reflection.Assembly]::LoadFrom("$HOME\ssh_net\lib\net462\Renci.SshNet.dll") | Out-Null

function Invoke-RemoteSSH {
    param (
        [string]$Command,
        [int]$TimeoutSeconds = 600
    )

    $client = New-Object Renci.SshNet.SshClient("173.212.197.126", "root", "aBh4HperXWe2923hW3r1d6d3JOTl")
    $client.ConnectionInfo.Timeout = [System.TimeSpan]::FromSeconds($TimeoutSeconds)
    $client.Connect()
    
    if (-not $client.IsConnected) {
        throw "Failed to connect to VPS 173.212.197.126"
    }

    $cmd = $client.CreateCommand($Command)
    $cmd.CommandTimeout = [System.TimeSpan]::FromSeconds($TimeoutSeconds)
    
    Write-Host "Executing remote command: $Command" -ForegroundColor Cyan
    $result = $cmd.Execute()
    $exitStatus = $cmd.ExitStatus
    
    if ($exitStatus -ne 0) {
        Write-Host "Exit Code: $exitStatus" -ForegroundColor Yellow
        if ($cmd.Error) {
            Write-Host "Stderr: $($cmd.Error)" -ForegroundColor Red
        }
    }
    
    $client.Disconnect()
    $client.Dispose()

    return [PSCustomObject]@{
        ExitCode = $exitStatus
        Output = $result
        Error = $cmd.Error
    }
}
