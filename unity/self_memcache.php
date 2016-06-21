<?php

require_once  'self_log.php';

class CMyMemcache {
	
	private $memcache;
	
	//构造函数
	public function __construct() {		
	}
	
	//析构函数
	public function __destruct() {
	}
	
	//默认连接
	public function memcache_default_connect() {
		//memcache扩展是否开启
		if (!extension_loaded('memcache')) {
			writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__).' not open memcache extension',LOG_NAME::ERROR_SYSTEM_ERROR_LOG_FILE_NAME);
			return false;
		}
		require_once 'self_memcache_config.php';
		$this->memcache = new Memcache;
		if (!$this->memcache->connect($memcache_config['hostname'],$memcache_config['port'],$memcache_config['timeout'])) {
			writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__).' connect memcache falure,host:'.$memcache_config['hostname'].
					' port:'.$memcache_config['port'].' timeout:'.$memcache_config['timeout'],LOG_NAME::ERROR_SYSTEM_ERROR_LOG_FILE_NAME);
			return false;
		}
		return true;
	}
	
	//自带参数连接
	/*@description:
	 * 建立一个到memcached服务端的连接。 打开的连接在脚本执行结束后会自动关闭。当然，你也可以使用方法 close()来主动关闭
	 * 
	 *@param:
	 *host:memcached服务端监听主机地址。这个参数也可以指定为其他传输方式比如unix:///path/to/memcached.sock 来使用Unix域socket，在这种方式下，port参数必须设置为0
	 *port:memcached服务端监听端口。当使用Unix域socket的时候要设置此参数为0
	 *timeout:连接持续（超时）时间，单位秒。默认值1秒，修改此值之前请三思，过长的连接持续时间可能会导致失去所有的缓存优势。
	 *
	 *@return:成功时返回 TRUE,或者在失败时返回 FALSE。
	*/
	public function memcache_connect($host,$port,$timeout=1) {
		//memcache扩展是否开启
		if (!extension_loaded('memcache')) {
			writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__).' not open memcache extension',LOG_NAME::ERROR_SYSTEM_ERROR_LOG_FILE_NAME);
			return false;
		} 
		$this->memcache = new Memcache;
		if (!$this->memcache->connect($host, $port,$timeout)) {
			writeLog(get_str_log_prex(__FILE__,__LINE__,__FUNCTION__).' connect memcache falure,host:'.$host.
					' port:'.port.' connect_timeout:'.$timeout,LOG_NAME::ERROR_SYSTEM_ERROR_LOG_FILE_NAME);
			return false;
		}
		return true;	
	}
	
	/*
	 * @description:关闭到memcached服务端的连接。这个函数不会关闭持久化连接， 持久化连接仅仅会在web服务器关机/重启时关闭
	 * @return:成功时返回 TRUE,或者在失败时返回 FALSE。
	 * */
	public function memcache_close() {
		return $this->memcache->close();	
	}
	
	/*
	 * @description:在缓存服务器之前不存在key时， 以key作为key存储一个变量var到缓存服务器
	 * @param:
	 * key:将要分配给变量的key。
	 * var:将要被存储的变量。字符串和整型被以原文存储，其他类型序列化后存储。
	 * flag:使用MEMCACHE_COMPRESSED标记对数据进行压缩(使用zlib)。
	 * expire:当前写入缓存的数据的失效时间。如果此值设置为0表明此数据永不过期。你可以设置一个UNIX时间戳或 以秒为单位的整数（从当前算起的时间差）来说明此数据的过期时间，但是在后一种设置方式中，不能超过 2592000秒（30天）
	 * @return:成功时返回 TRUE,或者在失败或者key值存在时返回 FALSE。
	 * */
	public function memcache_add($key,$var,$flag=false,$expire) {
		return $this->memcache->add($key,$var,$flag,$expire);
	}
	
	
	/*
	 * @description:增加一个服务器到连接池中。打开的连接将会在脚本执行结束后自动关闭，也可以使用close()进行手动关闭。
	 * 当使用这个方法的时候(与connect()和pconnect()相反) 网络连接并不会立刻建立，而是直到真正使用的时候才建立。 因此在加入大量服务器到连接池中时也是没有开销的，因为它们可能并不会被使用。
	 * 故障转移可能在方法的任何一个层次发生，通常只要其他服务器可用用户就不会感受到。任何的socket或memcache服务器级别的错误 （比如内存溢出）都可能导致故障转移。而一般的客户端错误比如使用add尝试增加一个已经存在的key则不会导致故障转移
	 * @param：
	 * host:要连接的memcached服务端监听的主机位置。这个参数通常指定其他类型的传输比如Unix域套接字使用 unix:///path/to/memcached.sock，这种情况下参数port 必须设置为0
	 * port:要连接的memcached服务端监听的端口。当使用UNIX域套接字连接时设置为0。
	 * persistent:控制是否使用持久化连接。默认TRUE。
	 * weight:为此服务器创建的桶的数量，用来控制此服务器被选中的权重，单个服务器被选中的概率是相对于所有服务器weight总和而言的。
	 * timeout:连接持续（超时）时间（单位秒），默认值1秒，修改此值之前请三思，过长的连接持续时间可能会导致失去所有的缓存优势。
	 * retry_interval:服务器连接失败时重试的间隔时间，默认值15秒。如果此参数设置为-1表示不重试。此参数和persistent参数在扩展以 dl()函数动态加载的时候无效
	 * 每个失败的连接结构有自己的超时时间，并且在它失效之前选择后端服务请求时该结构会被跳过。一旦一个连接失效， 它将会被成功重新连接或被标记为失败连接以在下一个retry_interval秒重连。 
	 * 典型的影响是每个web服务子进程在服务于一个页面时将会每retry_interval秒 尝试重新连接一次。
	 * status:控制此服务器是否可以被标记为在线状态。设置此参数值为FALSE并且retry_interval参数 设置为-1时允许将失败的服务器保留在一个池中以免影响key的分配算法。
	 * 对于这个服务器的请求会进行故障转移或者立即失败，这受限于memcache.allow_failover参数的设置。该参数默认TRUE，表明允许进行故障转移
	 * failure_callback:允许用户指定一个运行时发生错误后的回调函数。回调函数会在故障转移之前运行。回调函数会接受到两个参数，分别是失败主机的 主机名和端口号。
	 * timeoutms:
	 * @return 成功时返回 TRUE， 或者在失败时返回 FALSE
	 * */
	public function memcache_addServer($host,$port,$persistent=true,$weight=1,$timeout=1,$retry_interval=15,$status=true,$failure_callback=NULL,$timeoutms=1000) {
		return $this->memcache->addServer($host,$port);	
	}
	
	/*
	 * @description:方法将元素的值减小value,首先将元素当前值转换成数值然后减去value,
	 * @note,1)新的元素的值不会小于0,2)不要将方法用于压缩存储的元素，那样作会导致get()方法获取值会失败,3)在元素不存在时不能创建它
	 * @param:
	 * key:要减小值的元素的key
	 * value:value参数指要将指定元素的值减小多少
	 * @return:
	 * 成功的时候返回元素的新值 或者在失败时返回 FALSE
	 * */
	public function memcache_decrement($key,$value) {
		return $this->memcache->decrement($key,$value);	
	}
	
	/*@description:通过key删除一个元素。 如果参数timeout指定，该元素会在timeout秒后失效
	 *@param:
	 *key:要删除的元素的key
	 *timeout:删除该元素的执行时间(不推荐设置该值)。如果值为0,则该元素立即删除，如果值为30,元素会在30秒内被删除。
	 *@return:
	 *成功时返回 TRUE， 或者在失败时返回 FALSE。
	 * */
	public function memcache_delete($key,$timeout=0) {
		return $this->memcache->delete($key,$timeout);	
	}
	
	/*
	 * @description:立即使所有已经存在的元素失效。并不会真正的释放任何资源，而是仅仅标记所有元素都失效了，因此已经被使用的内存会被新的元素复写。
	 * @return:成功时返回 TRUE， 或者在失败时返回 FALSE。
	 * **/
	public function memcache_flush() {
		return $this->memcache->flush();	
	}
	
	/*
	 * @description:如果服务端之前有以key作为key存储的元素，返回之前存储的值。你可以传递一个数组（多个key）来获取一个数组的元素值，返回的数组仅仅包含从 服务端查找到的key-value对
	 * @return:返回key对应的存储元素的字符串值或者在失败或key未找到的时候返回FALSE。
	 * */
	public function memcache_get($key) {
		return $this->memcache->get($key);	
	}
	public function memcache_array_get($arr_key = array()) {
		return $this->memcache->get($arr_key);	
	}
	
	/*@description:
	 * 返回一个二维关联数据的服务器统计信息。数组的key由host:port方式 组成，无效的服务器返回的统计信息被设置为false。
	 * (译注)获取Memcache内所有数据方法：首先使用getExtendedStats('slabs')获取到每个服务器上活动slabs分块的id， 
	 * 然后 使用getExtendedStats('cachedump', $slabid, $limit)来获取每个slab里面缓存的项，其中$slabid是slab分块id， $limit指 期望获取其中的多少条记录。
	 * @param：
	 * type:期望抓取的统计信息类型，可以使用的值有{reset, malloc, maps, cachedump, slabs, items, sizes}。 通过memcached协议指定这些附加参数是为了方便memcache开发者(检查其中的变动)。
	 * slabid:用于与参数type联合从指定slab分块拷贝数据，cachedump命令会完全占用服务器通常用于 比较严格的调试。
	 * limit:用于和参数type联合来设置cachedump时从服务端获取的实体条数。
	 * @return:
	 * 返回一个二维关联数组的服务器统计信息或者在失败时返回FALSE。
	 * */
	public function memcache_default_getExtendedStats() {
		return $this->memcache->getExtendedStats();	
	}
	public function memcache_getExtendedStats($type,$slabid,$limit = 100) {
		return $this->memcache->getExtendedStats($type,$slabid,$limit);		
	}
	
	/*
	 * @description:返回一个服务器的在线/离线状态,这个函数在memcache2.1.0版本加入
	 * @param:
	 * host:主机监听地址。
	 * port:主机监听端口，默认11211.
	 * @return：
	 * 返回一个服务器的状态，0表示服务器离线，非0表示在线。
	 * */
	public function memcache_getServerStatus($host,$port) {
		return $this->memcache->getServerStatus($host, $port);	
	}
	
	/*
	 * @description:一个关联数据的服务器统计信息
	 * @param:
	 * type:期望抓取的统计信息类型，可以使用的值有{reset, malloc, maps, cachedump, slabs, items, sizes}。 通过memcached协议指定这些附加参数是为了方便memcache开发者(检查其中的变动)。
	 * slabid:用于与参数type联合从指定slab分块拷贝数据，cachedump命令会完全占用服务器通常用于 比较严格的调试。
	 * limit:用于和参数type联合来设置cachedump时从服务端获取的实体条数
	 * @return:
	 * 返回关联数组表示的服务器统计信息 或者在失败时返回 FALSE
	 * */
	public function memcache_default_getStats() {
		return $this->memcache->getStats();		
	}
	public function memcache_getStats($type,$slabid,$limit = 100) {
		return $this->memcache->getStats($type,$slabid,$limit);	
	}
	
	/*
	 * @description:
	 * 返回一个字符串表示的服务端版本号
	 * @return:
	 * 返回服务端版本号 或者在失败时返回 FALSE
	 * */
	public function memcache_getVersion() {
		return $this->memcache->getVersion();		
	}
	
	/*
	 * @description:
	 * 将指定元素的值增加value。如果指定的key对应的元素必须是数值类型,key对应元素不存在时创建元素。
	 * @param:
	 * key:将要增加值的元素的key。
	 * value:参数value表明要将指定元素值增加多少。
	 * @return:
	 * 成功时返回新的元素值 或者在失败时返回 FALSE
	 * */
	public function memcache_increment($key,$value) {
		return $this->memcache->increment($key,$value);	
	}
	
	/*
	 * @description:打开一个到服务器的持久化连接。 这个连接不会在脚本执行结束后或者:close()被调用后关闭。
	 * @param:
	 * host:服务端监听的主机地址。这个参数还可以指定为其他传输方式比如unix:///path/to/memcached.sock 来使用Unix域套接字，使用这种方式port参数必须设置为0。
	 * port:服务端监听的端口号。使用Unix域套接字的时候需要将这个参数设置为0。
	 * timeout:连接持续（超时）时间，单位秒。默认值1秒，修改此值之前请三思，过长的连接持续时间可能会导致失去所有的缓存优势
	 * @return:
	 * 返回一个 Memcache 对象 或者在失败时返回 FALSE.
	 * */
	public function memcache_pconnect($host,$port,$timeout) {
		return $this->memcache->pconnect($host,$port,$timeout);		
	}
	
	/*
	 * @description:通过key来查找元素并替换其值。当key 对应的元素不存在时,返回FALSE。其他方面replace()的行为和set()一样
	 * @param:
	 * key:期望替换值的元素的key。
	 * var:将要存储的新的值，字符串和数值直接存储，其他类型序列化后存储。
	 * flag:使用MEMCACHE_COMPRESSED指定对值进行压缩(使用zlib)。
	 * expire:当前写入缓存的数据的失效时间。如果此值设置为0表明此数据永不过期。你可以设置一个UNIX时间戳或 以秒为单位的整数（从当前算起的时间差）来说明此数据的过期时间，但是在后一种设置方式中，不能超过 2592000秒（30天）。
	 * @return:成功时返回 TRUE， 或者在失败时返回 FALSE。
	 * */
	public function memcache_replace($key,$var,$flag,$expire) {
		return $this->memcache->replace($key,$var,$flag,$expire);		
	}
	
	/*
	 *@description:向key存储一个元素值为 var。参数expire是以秒为单位的失效时间， 如果设置为0表明该元素永不过期（但是它可能会因为为了给其他项分配空间而被删除）。如果你希望存储的元素 经过压缩（使用zlib），你可以设置flag的值为MEMCACHE_COMPRESSED
	 *@param:
	 *key:要设置值的key。
	 *var:要存储的值，字符串和数值直接存储，其他类型序列化后存储。
	 *flag:使用MEMCACHE_COMPRESSED指定对值进行压缩(使用zlib)
	 *expire:当前写入缓存的数据的失效时间。如果此值设置为0表明此数据永不过期。你可以设置一个UNIX时间戳或 以秒为单位的整数（从当前算起的时间差）来说明此数据的过期时间，但是在后一种设置方式中，不能超过 2592000秒（30天）
	 *return:成功时返回 TRUE， 或者在失败时返回 FALSE。
	 * */
	public function memcache_set($key,$var,$flag,$expire) {
		return $this->memcache->set($key,$var,$flag,$expire);	
	}
	
	/*
	 * @description:开启对于大值的自动压缩,此函数在memcache2.0.0加入
	 * @param:
	 * threshold:控制多大值进行自动压缩的阈值。
	 * min_savings:经过压缩实际存储的值的压缩率，支持的值必须在0和1之间。默认值是0.2表示20%压缩率
	 * @return:成功时返回 TRUE， 或者在失败时返回 FALSE
	 * **/
	public function memcache_setCompressThreshold($threshold,$min_savings) {
		return $this->memcache->setCompressThreshold($threshold,$min_savings);	
	}
	
	/*
	 * @description:用于运行时修改服务器参数,在memcache2.1.0加入
	 * @param：
	 * host:服务端监听地址
	 * port:服务端监听端口
	 * timeout:连接持续（超时）时间（单位秒），默认值1秒，修改此值之前请三思，过长的连接持续时间可能会导致失去所有的缓存优势。
	 * retry_interval:服务器连接失败时重试的间隔时间，默认值15秒。如果此参数设置为-1表示不重试。此参数和persistent参数在扩展以 dl()函数动态加载的时候无效。
	 * status:控制此服务器是否可以被标记为在线状态。设置此参数值为FALSE并且retry_interval参数 设置为-1时允许将失败的服务器保留在一个池中以免影响key的分配算法。对于这个服务器的请求会进行故障转移或者立即失败， 这受限于memcache.allow_failover参数的设置。该参数默认TRUE，表明允许进行故障转移
	 * failure_callback:允许用户指定一个运行时发生错误后的回调函数。回调函数会在故障转移之前运行。回调函数会接受到两个参数，分别是失败主机的 主机名和端口号。
	 * */
	public function memcache_setServerParams($host,$port=11211,$timeout=1,$retry_interval=15,$status=true,$failure_callback=NULL) {
		return $this->memcache->setServerParams($host,$port,$timeout,$retry_interval,$status,$failure_callback);	
	}
	
	/*
	 * @description:
	 * 转换调试输出的开/关,在参数 on_off设置为TRUE时打开调试输出，在值为 FALSE时关闭调试输出
	 * @note:memcache_debug()仅仅在PHP以--enable-debug方式编译时可以访问，并且 总是返回TRUE，其他情况下此函数不会产生任何影响并总是返回FALSE。
	 * @param:
	 * on_off:TRUE表明打开调试输出。 FALSE表明关闭调试输出。
	 * @return:
	 * 当PHP以--enalbe-debug选项构建时返回TRUE其他情况下返回FALSE。
	 * */
	public function memcache_memcache_debug($on_off) {
		return $this->memcache->memcache_debug($on_off);	
	}
}

?>

