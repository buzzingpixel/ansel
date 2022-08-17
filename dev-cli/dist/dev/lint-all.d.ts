import { Command } from '@oclif/core';
export default class LintAll extends Command {
    static summary: string;
    run(): Promise<void>;
}
