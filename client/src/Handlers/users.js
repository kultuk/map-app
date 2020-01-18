export default  {
    async login(username,password, isNew){
        var requestUrl;
        if(isNew){
            requestUrl = global.c.SERVER_LOCATIONS + "/register";
        }else{
            requestUrl = global.c.SERVER_LOCATIONS + "/login";
        }
        return fetch(requestUrl,
        {
            method: 'POST',
            body: JSON.stringify({username,password})
        }).then(function (result) {
            return(result.json());
        })
    },
    logout(){
        localStorage.removeItem("auth-token");
        //TODO: route back to login/register
    }
}