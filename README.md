# phpのエラー・警告文を翻訳致します（translate an error, a warning sentence of php）
phpのエラー文章を翻訳して、書き出します。
出力にはgettext()に準していますが、使用していません。
言語ファイル(./languages)ファイルを追加・変更すれば様々なエラー文を翻訳することができます。

translate an error sentence of php and begin to write it.
In the output in gettext() associate; do it, but do not use it.
If I add language file (./languages) file and change it, I can translate various error sentences.

## 使い方(how to use)
### init.phpを読み込みます(include init.php)
    include_once ("init.php");

### 言語を設定致します。(setup language)
    PHPLangError::init("ja");

### 使用例(Example)
#### normal
    Use of undefined constant **** - assumed '****'
#### output
    タイプミスかもしれません('****')