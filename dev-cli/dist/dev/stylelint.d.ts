import { Command } from '@oclif/core';
export default class Stylelint extends Command {
    static summary: string;
    static flags: {
        fix: import("@oclif/core/lib/interfaces").BooleanFlag<boolean>;
    };
    run(): Promise<void>;
    static runStandAlone(rootPath: string, fix?: boolean): Promise<void>;
}
