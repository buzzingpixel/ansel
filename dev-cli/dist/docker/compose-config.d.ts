import { Command } from '@oclif/core';
export default class ComposeConfig extends Command {
    static summary: string;
    run(): Promise<void>;
}
