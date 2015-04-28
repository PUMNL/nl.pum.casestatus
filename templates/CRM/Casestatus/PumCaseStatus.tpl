{literal}
  <script type="text/javascript">
    cj('#case_type_id').change(function() {
      var caseTypeId = cj('#case_type_id').val();
      switch(caseTypeId) {
        case '6':
          cj('#status_id').val(5);
          break;
        case '19':
          cj('#status_id').val(13);
          break;
        case '15':
          cj('#status_id').val(6);
          break;
      }
    });
  </script>
{/literal}