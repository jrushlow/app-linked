#!/usr/bin/env bash

# Requires BASH >=4.0 (ascii colors)

projects_path="${HOME}/develop/php"
vendor_path="${PWD}/vendor"

declare -A app_paths    # Make app_paths an associative array
# key - relative path in vendor dir
# value - local path of the fork to by symlinked
# app_paths["symfony"]="forked-symfony"
app_paths["symfony/maker-bundle"]="forked-maker-bundle"
app_paths["symfonycasts/verify-email-bundle"]="forked-verify-email-bundle"
app_paths["symfonycasts/reset-password-bundle"]="forked-reset-password-bundle"
declare -r app_paths    # Make app_paths readonly

declare -A text         # Make text an associative array
text[normal]="\e[0m"
text[red]="\e[31m"
text[green]="\e[32m"
text[yellow]="\e[33m"
text[cyan]="\e[36m"
declare -r text         # Make text readonly

echo -e "Projects Path: ${projects_path}\n"

for i in "${!app_paths[@]}"; do
  source_path="${projects_path}/${app_paths[$i]}"
  dest_path="${vendor_path}/${i}"

  echo -e "${text[green]}$dest_path${text[normal]}:"
  echo " - Removing path if it exists..."
  rm -rf "${dest_path}"
  echo -e " - Creating symlink for: ${text[green]}${source_path}${text[normal]}"



  if ln -sf "${source_path}" "${dest_path}"; then
    echo -e " - ${text[green]}SUCCESS!${text[normal]}"
  else
    echo -e " - ${text[red]}Oops! Symlink not created... Try running \"${text[yellow]}composer require ${i}${text[red]}\" then run this command again.${text[normal]}"
  fi

  echo ""
done
