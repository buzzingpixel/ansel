import { Command } from '@oclif/core';
export default class Localtunnel extends Command {
    static summary: string;
    run(): Promise<void>;
    private runSite;
}
