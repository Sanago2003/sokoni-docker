<?php
require '../config.php';
header('Content-Type: application/json; charset=utf-8');
try{
 $search=trim($_GET['search']??'');$type=trim($_GET['type']??'');$region=(int)($_GET['region_id']??0);$crop=(int)($_GET['crop_id']??0);
 $sql="SELECT l.id,l.quantity,l.price,l.note,l.created_at,u.name,u.phone,c.name AS crop,un.name AS unit,r.name AS region,lt.name AS type FROM listings l JOIN users u ON u.id=l.user_id JOIN crops c ON c.id=l.crop_id JOIN units un ON un.id=l.unit_id JOIN regions r ON r.id=l.region_id JOIN listing_types lt ON lt.id=l.listing_type_id JOIN listing_statuses ls ON ls.id=l.status_id WHERE ls.name='active'";$p=[];
 if($search!==''){$sql.=' AND (c.name LIKE ? OR r.name LIKE ? OR u.name LIKE ?)';$q="%$search%";array_push($p,$q,$q,$q);}if($type!==''){$sql.=' AND lt.name=?';$p[]=$type;}if($region){$sql.=' AND l.region_id=?';$p[]=$region;}if($crop){$sql.=' AND l.crop_id=?';$p[]=$crop;}$sql.=' ORDER BY l.created_at DESC';$s=$pdo->prepare($sql);$s->execute($p);echo json_encode(['success'=>true,'data'=>$s->fetchAll()],JSON_UNESCAPED_UNICODE);
}catch(Throwable $e){http_response_code(500);echo json_encode(['success'=>false,'error'=>$e->getMessage()]);}
