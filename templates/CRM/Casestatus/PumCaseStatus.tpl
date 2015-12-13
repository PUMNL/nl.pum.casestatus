{literal}
  <script type="text/javascript">
    cj('#case_type_id').change(function() {
      var caseTypeId = cj('#case_type_id').val();
      CRM.api('PumCaseStatus', 'Get', {"check_permissions":""}, {
        success: function(data){
          cj('#status_id').val(data.values[caseTypeId]['status_id']);
        }
      });
    });
    cj(document).ready(function() {
      var caseTypeId = cj('#case_type_id').val();
      CRM.api('PumCaseStatus', 'Get', {"check_permissions":""}, {
        success: function(data){
          cj('#status_id').val(data.values[caseTypeId]['status_id']);
        }
      });
    });
  </script>
{/literal}
