#!/usr/bin/env bash
# wait-for-it.sh

host="$1"
shift
cmd="$@"

echo "Waiting for MySQL at $host..."

until mysql -h "$host" -u root -pCristiano7! -e 'SELECT 1' judging_system &>/dev/null; do
  >&2 echo "MySQL is unavailable - sleeping"
  sleep 2
done

>&2 echo "MySQL is up - executing command"
exec $cmd
