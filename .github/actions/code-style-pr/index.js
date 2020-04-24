const core = require('@actions/core');
const exec = require('@actions/exec');
const github = require('@actions/github');

async function run() {
    try {
        try {
            const phpcsOutput = await exec.exec('vendor/bin/phpcs', [], { ignoreReturnCode: true });
            console.log(phpcsOutput);
        } catch (phpcsError) {
            console.error(phpcsError);
        }
    } catch (e) {
        core.setFailed(e.message);
    }
}

run();