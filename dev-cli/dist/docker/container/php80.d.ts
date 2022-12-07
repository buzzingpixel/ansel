import { Command } from '@oclif/core';
export default class Php80 extends Command {
    static summary: string;
    static args: {
        name: string;
        description: string;
        default: null;
    }[];
    run(): Promise<void>;
    runCommand(cmd: string): Promise<void>;
}
