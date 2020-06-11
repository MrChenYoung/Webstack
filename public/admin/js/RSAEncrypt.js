
// 公钥加密
function publicEncrypt(publicKey,encryptContent) {
    let encryptor = new JSEncrypt();
    encryptor.setPublicKey(publicKey)
    return encryptor.encrypt(encryptContent)
}

// 私钥加密
function privateEncrypt(privateKey,encryptContent) {
    let encryptor = new JSEncrypt();
    encryptor.setPrivateKey(privateKey)
    return encryptor.encrypt(encryptContent)
}

// 公钥解密
function publicDecrypt(publicKey,decryptContent) {
    let encryptor = new JSEncrypt();
    encryptor.setPublicKey(publicKey)
    return encryptor.decrypt(encryptContent)
}

// 私钥解密
function privateDecrypt(privateKey,decryptContent) {
    let encryptor = new JSEncrypt();
    encryptor.setPrivateKey(privateKey)
    return encryptor.decrypt(decryptContent)
}