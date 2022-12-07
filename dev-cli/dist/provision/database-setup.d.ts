import { Command } from '@oclif/core';
export default class DatabaseSetup extends Command {
    static summary: string;
    run(): Promise<void>;
}
