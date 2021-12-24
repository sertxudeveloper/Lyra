<?php

namespace SertxuDeveloper\Lyra\Console;

use Illuminate\Console\Command as ConsoleCommand;

abstract class Command extends ConsoleCommand {

  /**
   * Replace the $search with $replace in the $path file.
   *
   * @param string $search The string to search for.
   * @param string $replace The string to replace with.
   * @param string $path The path to the file.
   */
  protected function replace(string $search, string $replace, string $path) {
    file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
  }

  /**
   * Write the $content in the $path file.
   *
   * @param string $path The path to the file.
   * @param string $content The content to be written
   * @return false|int Number of bytes that were written or false on failure.
   */
  protected function writeFile(string $path, string $content): bool|int {
    return file_put_contents($path, $content);
  }

  /**
   * Read content of the $path file.
   *
   * @param string $path Path to the file.
   * @return bool|string Content of the file or false on failure.
   */
  protected function readFile(string $path): bool|string {
    return file_get_contents($path);
  }
}
