<?php
class SFTP{
    public function uploadFile($sftp, $file_data, $paramData){
        $sftp->chdir('uploads');
        if($sftp->file_exists($file_data['name'])){
            if(!isset($paramData['fileHandling'])){
                $msg = array('statusCode' => NET_SFTP_STATUS_FILE_ALREADY_EXISTS, 'statusInformation' => 'File Already exists');
            }
            else{
                if($paramData['fileHandling'] == '1'){
                    $sftp->delete($file_data['name']);
                    $sftp->put($file_data['name'], $file_data['tmp_name'], $sftp::SOURCE_LOCAL_FILE);
                    $msg = array('statusCode' => NET_SFTP_STATUS_FILE_ALREADY_EXISTS, 'statusInformation' => 'New File uploaded and replaced with existing file');
                }
                else if($paramData['fileHandling'] == '2'){
                    $sftp->rename($file_data['name'], $file_data['name'].' ('.date("Y-m-d-h:i:sa").')');
                    $sftp->put($file_data['name'], $file_data['tmp_name'], $sftp::SOURCE_LOCAL_FILE);
                    $msg = array('statusCode' => NET_SFTP_STATUS_FILE_ALREADY_EXISTS, 'statusInformation' => 'New File uploaded successfully and existing file is renamed.');
                }
                else{
                    $msg = array('statusCode' => NET_SFTP_STATUS_INVALID_PARAMETER, 'statusInformation' => 'Error in passing parameters, Make sure you are passing the parameters correctly');
                }
            }
        }
        else {
            $sftp->put($file_data['name'], $file_data['tmp_name'], $sftp::SOURCE_LOCAL_FILE);
            $msg = array('statusCode' => NET_SFTP_STATUS_OK, 'statusInformation' => 'New File uploaded successfully');
        }
        return $msg;
    }   

    public function getAllFiles($sftp){
        try {
            $sftp->chdir('uploads');
            $list = $sftp->nlist();
            $msg = array('statusCode' => NET_SFTP_STATUS_OK, 'statusInformation' => $list);
            return $msg;
        } catch (\Exception $msg) {
            $msg = $sftp->getSFTPErrors();
            return $msg;
        }
    }

    
    public function downloadFiles($sftp, $paramData){
        if($paramData['downloadFileType'] == 1){
            if($sftp->is_file($paramData['file'])){
                $sftp->get($paramData['file'], '../sql/' .$paramData['file']);
                $msg = array('statusCode' => NET_SFTP_STATUS_OK, 'statusInformation' => 'File Downloaded successfully');
            }
            else {
                $msg = array('statusCode' => NET_SFTP_STATUS_FILE_IS_A_DIRECTORY, 'statusInformation' => 'Download file type is File not a Directory, you can not download directory without passing correct parameter.');
            }
        }
        else if($paramData['downloadFileType'] == 2){
            if($sftp->is_dir($paramData['file'])){
                mkdir('../sql/' .$paramData['file']);
                $sftp->chdir($paramData['file']);
                $n = $sftp->nlist();
                for($i = 0; $i < sizeof($n); $i++){
                    if(!$sftp->is_dir($n[$i])){
                        $sftp->get($n[$i], '../sql/' .$paramData['file']. '/' .$n[$i]);
                    }
                    else{
                        if(!is_dir('../sql/' .$paramData['file']. '/' .$n[$i])){
                            mkdir('../sql/' .$paramData['file']. '/' .$n[$i], 0777, true);
                        }
                    } 
                } 
                $msg = array('statusCode' => NET_SFTP_STATUS_OK, 'statusInformation' => 'Folder Downloaded successfully');
            }
            else {
                $msg = array('statusCode' => NET_SFTP_STATUS_NOT_A_DIRECTORY, 'statusInformation' => 'Download file type is a Directory not a File, you can not download directory without passing correct parameter.');
            }
            
        }
        else {
            $msg = array('statusCode' => NET_SFTP_STATUS_INVALID_PARAMETER, 'statusInformation' => 'Error in passing parameters, Make sure you are passing the parameters correctly');
        }
        
        return $msg;
    }
}
