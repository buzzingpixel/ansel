# Start with the gitpod base image
FROM gitpod/workspace-full:2022-05-08-14-31-53
USER gitpod

# Set up our PHP version
RUN brew install php@7.4 && brew link php@7.4
RUN sudo ln -sf $(which php) /usr/bin/php74

# Install the correct composer version
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Install EXA for nicer file listings
RUN brew install exa

# Add our fish preferences
COPY .config/fish /home/gitpod/.config/fish

# Set the workdir
WORKDIR /workspace/ansel
