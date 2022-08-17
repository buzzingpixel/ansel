import { Command } from '@oclif/core';
export default class Tsc extends Command {
    static summary: string;
    run(): Promise<void>;
}
