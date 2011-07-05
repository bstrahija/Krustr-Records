<hgroup class="pg"><div class="w">
	<h1><em class="picto write"></em> Edit file</h1>
</div></hgroup>

<section class="mainc">
	<div class="block start">
		<textarea class="code" id="code" style="width: 90%; height: 400px;"><?php echo htmlentities($content); ?></textarea>
		
		<script src="<?php echo site_url('system/assets/js/libs/code_mirror/codemirror.js'); ?>"></script>
		<script>
		var css_path = "<?php echo site_url('system/assets/css/libs/code_mirror'); ?>/";
		var editor = CodeMirror.fromTextArea('code', {
        height: "350px",
        parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js",
                     "tokenizephp.js", "parsephp.js",
                     "parsephphtmlmixed.js"],
        stylesheet: [css_path+"xmlcolors.css", css_path+"jscolors.css", css_path+"csscolors.css", css_path+"phpcolors.css"],
        path: "<?php echo site_url('system/assets/js/libs/code_mirror'); ?>/",
        continuousScanning: 500
      });
		
		
		
		
		/*var editor = new CodeMirror(CodeMirror.replace("inputfield"), {
  parserfile: ["tokenizephp.js", "parsephp.js"],
  path: "<?php echo site_url('system/assets/js/libs/code_mirror'); ?>/",
  stylesheet: "<?php echo site_url('system/assets/css/libs/code_mirror'); ?>/phpcolors.css",
  content: document.getElementById("inputfield").value
});*/
		</script>
	</div>
	<!-- /.box -->
</section>
<!-- .mainc -->