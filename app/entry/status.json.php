<?php
RespondJson::PrintResult(array('error'=>0,'eid'=>$eid,'vid'=>$vid,'status'=>$entry['is_public'],'forkable'=>$entry['is_forkable']));
?>
