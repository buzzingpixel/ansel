import { Command } from '@oclif/core';
export default class EnsureDotEnv extends Command {
    static summary: string;
    static hidden: boolean;
    run(): Promise<void>;
}
