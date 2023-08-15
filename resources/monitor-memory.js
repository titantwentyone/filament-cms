function logMemoryUsage() {
    const memoryUsage = process.memoryUsage();
    const toMB = bytes => (bytes / (1024 * 1024)).toFixed(2);

    console.log('Memory usage:');
    console.log(`  RSS: ${toMB(memoryUsage.rss)} MB`);
    console.log(`  Heap Total: ${toMB(memoryUsage.heapTotal)} MB`);
    console.log(`  Heap Used: ${toMB(memoryUsage.heapUsed)} MB`);
    console.log(`  External: ${toMB(memoryUsage.external)} MB`);
}

module.exports = {
    logMemoryUsage
};