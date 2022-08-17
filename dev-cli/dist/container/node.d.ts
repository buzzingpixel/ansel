import { Command } from '@oclif/core';
export default class Node extends Command {
    static summary: string;
    static args: {
        name: string;
        description: string;
        default: null;
    }[];
    run(): Promise<void>;
}
