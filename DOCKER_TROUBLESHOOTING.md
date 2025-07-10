# Docker Troubleshooting Guide

## Common Issues and Solutions

### 1. Docker Desktop Not Running

**Error:** `error during connect: Head "http://%2F%2F.%2Fpipe%2FdockerDesktopLinuxEngine/_ping": open //./pipe/dockerDesktopLinuxEngine: The system cannot find the file specified.`

**Solution:**
1. **Start Docker Desktop:**
   - Look for Docker Desktop in your system tray (bottom right)
   - If not there, search for "Docker Desktop" in Start menu
   - Launch Docker Desktop and wait for it to fully start (whale icon should be steady)

2. **Check Docker Desktop Status:**
   ```powershell
   # Check if Docker is running
   docker --version
   docker info
   ```

3. **If Docker Desktop is not installed:**
   - Download from: https://www.docker.com/products/docker-desktop/
   - Install and restart your computer
   - Enable WSL 2 if prompted

### 2. WSL 2 Issues

**If you see WSL 2 related errors:**

1. **Enable WSL 2:**
   ```powershell
   # Run as Administrator
   dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart
   dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart
   ```

2. **Restart your computer**

3. **Install WSL 2 kernel update:**
   - Download from: https://aka.ms/wsl2kernel

4. **Set WSL 2 as default:**
   ```powershell
   wsl --set-default-version 2
   ```

### 3. Hyper-V Issues

**If you see Hyper-V related errors:**

1. **Enable Hyper-V:**
   ```powershell
   # Run as Administrator
   Enable-WindowsOptionalFeature -Online -FeatureName Microsoft-Hyper-V -All
   ```

2. **Restart your computer**

### 4. Port Conflicts

**If ports 8000, 3306, or 6379 are already in use:**

1. **Check what's using the ports:**
   ```powershell
   netstat -ano | findstr :8000
   netstat -ano | findstr :3306
   netstat -ano | findstr :6379
   ```

2. **Stop conflicting services or change ports in docker-compose.yml**

### 5. Permission Issues

**If you get permission errors:**

1. **Run PowerShell as Administrator**

2. **Check Docker Desktop settings:**
   - Open Docker Desktop
   - Go to Settings > Resources > File Sharing
   - Add your project directory

### 6. Memory Issues

**If containers fail to start due to memory:**

1. **Increase Docker Desktop memory:**
   - Open Docker Desktop
   - Go to Settings > Resources > Advanced
   - Increase memory limit (recommend 4GB+)

### 7. Network Issues

**If containers can't communicate:**

1. **Reset Docker Desktop:**
   - Open Docker Desktop
   - Go to Settings > Troubleshoot
   - Click "Reset to factory defaults"

2. **Check Windows Firewall:**
   - Allow Docker Desktop through Windows Firewall

## Step-by-Step Setup for Windows

### Prerequisites Check

1. **Windows 10/11 Pro, Enterprise, or Education**
   - Home edition requires WSL 2

2. **Enable virtualization in BIOS**
   - Restart and enter BIOS
   - Enable Intel VT-x or AMD-V

3. **Install Docker Desktop**
   - Download from Docker website
   - Install with default settings
   - Restart computer

### First Time Setup

1. **Start Docker Desktop**
   - Wait for the whale icon to be steady (not animated)

2. **Verify Docker is working:**
   ```powershell
   docker --version
   docker run hello-world
   ```

3. **Run the setup script:**
   ```powershell
   .\docker-setup.bat
   ```

## Alternative Setup Methods

### Method 1: Manual Setup
```powershell
# Copy environment file
copy docker\env.example .env

# Build and start containers
docker-compose up -d --build

# Install dependencies
docker-compose exec app composer install
docker-compose exec node npm install

# Setup Laravel
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate

# Build assets
docker-compose exec node npm run build
```

### Method 2: Using WSL 2 Terminal
If you prefer using WSL 2 terminal:

1. **Install Ubuntu from Microsoft Store**
2. **Open Ubuntu terminal**
3. **Navigate to your project:**
   ```bash
   cd /mnt/c/Users/abdul/code/sales-tracker
   ```
4. **Run the bash setup script:**
   ```bash
   chmod +x docker-setup.sh
   ./docker-setup.sh
   ```

## Verification Commands

After setup, verify everything is working:

```powershell
# Check container status
docker-compose ps

# Check logs
docker-compose logs

# Test database connection
docker-compose exec db mysql -u sales_tracker -p sales_tracker -e "SELECT 1;"

# Test web application
curl http://localhost:8000
```

## Getting Help

If you're still having issues:

1. **Check Docker Desktop logs:**
   - Open Docker Desktop
   - Go to Settings > Troubleshoot
   - View logs

2. **Check Windows Event Viewer:**
   - Look for Docker-related errors

3. **Common solutions:**
   - Restart Docker Desktop
   - Restart your computer
   - Reset Docker Desktop to factory defaults
   - Reinstall Docker Desktop 