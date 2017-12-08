#!/bin/bash
rm thekofclient.php
cd ../source
find . -type f -name '*.php' -exec cat {} + >> ../bundle/thekofclient.php
cd ../bundle
sed -i 's/<[?]php namespace Talis\\Services\\TheKof;/\n/g' thekofclient.php
echo -e "<?php namespace Talis\\Services\\TheKof;\n$(cat thekofclient.php)" > thekofclient.php

