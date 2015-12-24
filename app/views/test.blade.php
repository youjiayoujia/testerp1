{{ $name = 'ok'}}
{{ var_dump($name) }}
{{ "<script>var test =\"$name\";</script>" }}
<script type='text/javascript'>
alert(1);
alert(test);
</script>