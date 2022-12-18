"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const core_1 = require("@oclif/core");
// eslint-disable-next-line @typescript-eslint/no-var-requires
const localtunnel = require('localtunnel');
// eslint-disable-next-line @typescript-eslint/no-var-requires
const style = require('cli-color');
const sites = [
    {
        port: 11374,
        subdomain: 'anselcraft3php74',
    },
    {
        port: 11380,
        subdomain: 'anselcraft3php80',
    },
    {
        port: 11381,
        subdomain: 'anselcraft3php81',
    },
    {
        port: 33774,
        subdomain: 'anselee7php74',
    },
    {
        port: 33780,
        subdomain: 'anselee7php80',
    },
    {
        port: 33781,
        subdomain: 'anselee7php81',
    },
    {
        port: 9980,
        subdomain: 'anseleecoilpackphp80',
    },
    {
        port: 9981,
        subdomain: 'anseleecoilpackphp81',
    },
];
class Localtunnel extends core_1.Command {
    async run() {
        sites.forEach((site) => {
            this.runSite(site);
        });
    }
    async runSite(site) {
        const tunnel = await localtunnel({
            port: site.port,
            subdomain: site.subdomain,
        });
        this.log(style.cyan(tunnel.url));
    }
}
exports.default = Localtunnel;
Localtunnel.summary = 'Starts localtunnel';
