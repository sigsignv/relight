<?php

declare(strict_types=1);

/**
 * test/bbs-main.php で thread/ ディレクトリにサブディレクトリを作成する目的で使われている。
 * オリジナルは戻り値を返していたが、利用されていなかったため省いている。
 *
 * @param string $path
 * @return void
 */
function makeDir(string $path): void {
    mkdir($path, 0777, true);
}
