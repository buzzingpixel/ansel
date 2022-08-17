import { Command } from '@oclif/core';
export default class Phpunit extends Command {
    static summary: string;
    run(): Promise<void>;
}
