<template>
  <textarea v-model="field.value" :id="field.column" cols="200" class="code-field"></textarea>
</template>

<script>
  import CodeMirror from 'codemirror'

  import  'codemirror/mode/clike/clike'
  import  'codemirror/mode/htmlmixed/htmlmixed'
  import  'codemirror/mode/javascript/javascript'
  import  'codemirror/mode/sql/sql'
  import  'codemirror/mode/shell/shell'
  import  'codemirror/mode/powershell/powershell'
  import  'codemirror/mode/css/css'
  import  'codemirror/mode/dockerfile/dockerfile'
  import  'codemirror/mode/go/go'
  import  'codemirror/mode/http/http'
  import  'codemirror/mode/markdown/markdown'
  import  'codemirror/mode/php/php'
  import  'codemirror/mode/python/python'
  import  'codemirror/mode/sass/sass'
  import  'codemirror/mode/scheme/scheme'
  import  'codemirror/mode/diff/diff'
  import  'codemirror/mode/vue/vue'
  import  'codemirror/mode/xml/xml'
  import  'codemirror/mode/yaml/yaml'

  import 'codemirror/addon/hint/anyword-hint'
  import 'codemirror/addon/hint/css-hint'
  import 'codemirror/addon/hint/html-hint'
  import 'codemirror/addon/hint/html-hint'
  import 'codemirror/addon/hint/javascript-hint'
  import 'codemirror/addon/hint/show-hint'
  import 'codemirror/addon/hint/sql-hint'
  import 'codemirror/addon/hint/xml-hint'

  import 'codemirror/addon/lint/css-lint'
  import 'codemirror/addon/lint/html-lint'
  import 'codemirror/addon/lint/javascript-lint'
  import 'codemirror/addon/lint/json-lint'
  import 'codemirror/addon/lint/lint'
  import 'codemirror/addon/lint/yaml-lint'

  import 'codemirror/addon/edit/closebrackets';

  import { JSHINT } from 'jshint'

  window.JSHINT = JSHINT;

  export default {
    data() {
      return {
        editor: null
      }
    },
    props: ['field', 'formData'],
    mounted() {
      this.editor = CodeMirror.fromTextArea(this.$el, {
        value: this.field.value,
        mode: this.field.mode,
        json: this.field.json,
        theme: 'vscode-dark',
        lineNumbers: true,
        readOnly: true,
        lint: true,
        lineWrapping: true,
        autoCloseBrackets: true,
        gutters: ["CodeMirror-lint-markers", "CodeMirror-linenumbers"],
        styleActiveLine: true,
      });

      this.editor.on("change", (event) => {
        this.field.value = event.getValue();
      });
    }
  };
</script>

<style>
  @import '~codemirror/addon/lint/lint.css';

  .code-field~.CodeMirror {
   padding: 0;
 }

  /**
 *	@package    vscode-dark theme
 *	@version    1.0.0
 *	@author		  Inter-Net PRO
 *	@link       https://inter-net.pro
 *	@github     https://github.com/Inter-Net-Pro/Theme-VSCode-Dark
 */
  .cm-s-vscode-dark span.cm-meta {color: #569cd6}
  .cm-s-vscode-dark span.cm-number {color: #b5cea8}
  .cm-s-vscode-dark span.cm-keyword {line-height: 1em; font-weight: bold; color: #569cd6;}
  .cm-s-vscode-dark span.cm-def {color:#9cdcfe}
  .cm-s-vscode-dark span.cm-variable {color: #ddd6a3}
  .cm-s-vscode-dark span.cm-variable-2 {color: #9cdcfe}
  .cm-s-vscode-dark span.cm-variable-3,
  .cm-s-vscode-dark span.cm-type {color: #A9B7C6}
  .cm-s-vscode-dark span.cm-property {color: #9cdcfe}
  .cm-s-vscode-dark span.cm-operator {color: #d4d4d4}
  .cm-s-vscode-dark span.cm-string {color: #ce9178}
  .cm-s-vscode-dark span.cm-string-2 {color: #6A8759}
  .cm-s-vscode-dark span.cm-comment {color: #6A9955}
  .cm-s-vscode-dark span.cm-link {color: #287BDE}
  .cm-s-vscode-dark span.cm-atom {color: #569cd6}
  .cm-s-vscode-dark span.cm-error {color: #BC3F3C}
  .cm-s-vscode-dark span.cm-tag {color: #569cd6}
  .cm-s-vscode-dark span.cm-attribute {color: #9cdcfe}
  .cm-s-vscode-dark span.cm-qualifier {color: #d7ba7d}
  .cm-s-vscode-dark span.cm-bracket {color: #808080}

  .cm-s-vscode-dark.CodeMirror {background: #1e1e1e; color: #e9e9e9;}
  .cm-s-vscode-dark .CodeMirror-cursor {border-left: 1px solid #bebebe;}
  .CodeMirror-activeline-background {background: #3A3A3A;}
  .cm-s-vscode-dark div.CodeMirror-selected {background: #1e496c}
  .cm-s-vscode-dark .CodeMirror-gutters {background: #252526; border-right: 1px solid grey; color: #606366}
  .cm-s-vscode-dark span.cm-builtin {color: #A9B7C6;}
  .cm-s-vscode-dark {font-family: Consolas, 'Courier New', monospace, serif;}
  .cm-s-vscode-dark .CodeMirror-matchingbracket {background-color: #3b514d; color: yellow !important;}

  .CodeMirror-hints.vscode-dark {
    font-family: Consolas, 'Courier New', monospace;
    color: #9c9e9e;
    background-color: #3b3e3f !important;
  }

  .CodeMirror-hints.vscode-dark .CodeMirror-hint-active {
    background-color: #494d4e !important;
    color: #9c9e9e !important;
  }
</style>
