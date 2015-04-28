{literal}
  <script type="text/javascript">
    cj('#case_type_id').change(function() {
      var caseTypeId = cj('#case_type_id').val();
      CRM.api('CaseStatus', 'Getdefault', {case_type_id:caseTypeId}, {
        success: function(result) {
          cj('#status_id').val(result.values);
        }
      });
    });
    cj(document).ready(function() {
      var caseTypeId = cj('#case_type_id').val();
      if (caseTypeId != '') {
        CRM.api('CaseStatus', 'Getdefault', {case_type_id: caseTypeId}, {
          success: function (result) {
            cj('#status_id').val(result.values);
          }
        });
      }
    });
  </script>
{/literal}