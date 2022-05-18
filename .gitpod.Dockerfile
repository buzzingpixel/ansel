# Start with the gitpod base image
FROM gitpod/workspace-full
USER gitpod

# Install EXA for nicer file listings
RUN brew install exa

# Add our bash preferences
COPY .config/.bash_profile /home/gitpod/.bash_profile
RUN echo "if [ -f ~/.bash_profile ]; then" >> .bashrc
RUN echo ". ~/.bash_profile" >> .bashrc
RUN echo "fi" >> .bashrc

# Add our fish preferences
COPY .config/fish /home/gitpod/.config/fish

# Set PHP version to 7.4
RUN sudo update-alternatives --set php $(which php7.4)
RUN sudo ln -sf $(which php7.4) /usr/bin/php74

# Set up PHP requirements
RUN sudo apt-get install -y \
    php-curl \
    php-zip
