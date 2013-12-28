# System-wide .bashrc file for interactive bash(1) shells.

# To enable the settings / commands in this file for login shells as well,
# this file has to be sourced in /etc/profile.

# If not running interactively, don't do anything
[ -z "$PS1" ] && return

### HISTORY
# don't put duplicate lines in the history. See bash(1) for more options
# force ignoredups and ignorespace
HISTCONTROL=ignoreboth

# append to the history file, don't overwrite it
shopt -s histappend
HISTSIZE=10000

### TERMINAL settings
# check the window size after each command and, if necessary,
# update the values of LINES and COLUMNS.
shopt -s checkwinsize

# make less more friendly for non-text input files, see lesspipe(1)
[ -x /usr/bin/lesspipe ] && eval "$(SHELL=/bin/sh lesspipe)"

# PS colors
if [ -x /usr/bin/tput ] && tput setaf 1 >&/dev/null; then
    # We have color support; assume it's compliant with Ecma-48
    # (ISO/IEC-6429). (Lack of such support is extremely rare, and such
    # a case would tend to support setf rather than setaf.)
    NOC="\[[0m\]"
    WHITE="\[[1m\]"
    GREY="\[[2m\]"
    UNDERLINE="\[[4m\]"
    DEFACE="\[[9m\]"
    DARK="\[[30m\]"
    RED="\[[31m\]"
    GREEN="\[[32m\]"
    YELOW="\[[33m\]"
    BLUE="\[[34m\]"
    PINK="\[[35m\]"
    AZURE="\[[36m\]"
    BDARK="\[[40m\]"
    BRED="\[[41m\]"
    BGREEN="\[[42m\]"
    BYELOW="\[[43m\]"
    BBLUE="\[[44m\]"
    BPINK="\[[45m\]"
    BAZURE="\[[46m\]"
    BWHITE="\[[7m\]"
    HDARK="\[[90m\]"
    HRED="\[[91m\]"
    HGREEN="\[[92m\]"
    HYELOW="\[[93m\]"
    HBLUE="\[[94m\]"
    HPINK="\[[95m\]"
    HAZURE="\[[96m\]"
    
    if [[ $USER == "root" ]] ; then
            COLORPS=$NOC$HRED
    else
            COLORPS=$NOC$WHITE
    fi
    PS1="$WHITE\h$HGREEN[$COLORPS\u$HGREEN]-($COLORPS\w$HGREEN)\n$COLORPS->$NOC "
    PS2="$COLORPS|-$NOC "
    PS4="$NOC$HYELOW!>$NOC "
else
    PS1='\h[\u]-(\w)\n-> '
    PS2='|- '
    PS4='!> '
fi

# CMD colors
# enable color support of ls and also add handy aliases
if [ -x /usr/bin/dircolors ]; then
    test -r ~/.dircolors && eval "$(dircolors -b ~/.dircolors)" || eval "$(dircolors -b)"
fi

alias ls='ls --color=auto'
alias dir='dir --color=auto'
alias vdir='vdir --color=auto'

alias grep='grep --color=auto'
alias fgrep='fgrep --color=auto'
alias egrep='egrep --color=auto'

# ALIASES
alias l='ls -AlhFv'

# Alias definitions.
# You may want to put all your additions into a separate file like
# ~/.bash_aliases, instead of adding them here directly.
# See /usr/share/doc/bash-doc/examples in the bash-doc package.

if [ -f ~/.bash_aliases ]; then
    . ~/.bash_aliases
fi

# BASH completition
if [ -f /etc/bash_completion ] && ! shopt -oq posix; then
    . /etc/bash_completion
fi

# if the command-not-found package is installed, use it
if [ -x /usr/lib/command-not-found -o -x /usr/share/command-not-found ]; then
	function command_not_found_handle {
	        # check because c-n-f could've been removed in the meantime
                if [ -x /usr/lib/command-not-found ]; then
		   /usr/bin/python /usr/lib/command-not-found -- $1
                   return $?
                elif [ -x /usr/share/command-not-found ]; then
		   /usr/bin/python /usr/share/command-not-found -- $1
                   return $?
		else
		   return 127
		fi
	}
fi


