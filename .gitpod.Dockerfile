FROM gitpod/workspace-full:2022-05-08-14-31-53

USER gitpod

RUN brew install exa

COPY .config/fish /home/gitpod/.config/fish

WORKDIR /workspace/ansel
