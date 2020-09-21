#!/bin/bash

cd /mnt/scc8t/zhousq
if [ ! -d "{$1}" ]
then
    mkdir {$1}
    cd {$1}
else cd /mnt/scc8t/zhousq/{$1}
fi

