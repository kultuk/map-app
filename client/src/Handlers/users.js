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
            var jsonPromise = result.json() 
            jsonPromise.then(login=>{
                localStorage.setItem('auth_token',login.accessToken);
            });
            return jsonPromise
        })
    },
    async getLocations(){
        var token = localStorage.getItem('auth_token')
        return fetch(global.c.SERVER_LOCATIONS +"/locations",{
            method:'GET',
            headers: {
                "auth-token": token 
            }
        }
        ).then(res=>res.json());
    },
    async addLocation({lon,lat}){
        var token = localStorage.getItem('auth_token')
        var addURL = global.c.SERVER_LOCATIONS +"/locations/lat/" + parseInt(lat,10) + "/lon/"+parseInt(lon,10);    
        return fetch(addURL,{
            method:'GET',
            headers: {
                "auth-token": token 
            }
        }
        ).then(res=>res.json());
    },
    logout(){
        localStorage.removeItem("auth_token");
    }
}