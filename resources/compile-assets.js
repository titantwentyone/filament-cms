const { logMemoryUsage } = require('./monitor-memory');
const { exec } = require('child_process');

// Run memory monitoring code before "npm run build"
logMemoryUsage();

// Run "npm run build"
const buildProcess = exec('npm run build', (error, stdout, stderr) => {
    if (error) {
        console.error('Error:', error);
        return;
    }
    // Remove empty lines from the output and log it
    const cleanedOutput = stdout.replace(/^\s*[\r\n]/gm, ''); // Remove empty lines
    if (cleanedOutput.trim()) {
        console.log('Build process output:', cleanedOutput);
    }
});

// Listen for the "exit" event of the build process
buildProcess.on('exit', (code) => {
    if (code === 0) {
        console.log('Build process completed successfully');
    } else {
        console.error(`Build process exited with code ${code}`);
    }

    // Run memory monitoring code after "npm run build"
    logMemoryUsage();
});