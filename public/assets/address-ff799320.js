var A=Object.defineProperty;var g=(s,t,e)=>t in s?A(s,t,{enumerable:!0,configurable:!0,writable:!0,value:e}):s[t]=e;var r=(s,t,e)=>(g(s,typeof t!="symbol"?t+"":t,e),e);import{C as y}from"./connection-13f4ed86.js";import{bn as m,aD as p}from"./index-792b7300.js";class u{constructor(t,e){r(this,"latitude");r(this,"longitude");this.latitude=t,this.longitude=e}static fromJson(t){return new u(t.latitude,t.longitude)}toLatLng(){return{lat:this.latitude,lng:this.longitude}}toJson(){return{latitude:this.latitude,longitude:this.longitude}}toString(){return`${this.latitude},${this.longitude}`}}class d{constructor(t){r(this,"id");r(this,"googleId");r(this,"firstName");r(this,"lastName");r(this,"emailAddress");r(this,"phoneNumber");r(this,"company");r(this,"street");r(this,"city");r(this,"provinceCode");r(this,"provinceName");r(this,"postcode");r(this,"countryCode");r(this,"coordinates");r(this,"arriveAt");r(this,"updatedAt");r(this,"createdAt");this.id=t.id,this.googleId=t.googleId,this.firstName=t.firstName,this.lastName=t.lastName,this.emailAddress=t.emailAddress,this.phoneNumber=t.phoneNumber,this.company=t.company,this.street=t.street,this.city=t.city,this.provinceCode=t.provinceCode,this.provinceName=t.provinceName,this.postcode=t.postcode,this.countryCode=t.countryCode,this.coordinates=t.coordinates,this.arriveAt=t.arriveAt,this.updatedAt=t.updatedAt,this.createdAt=t.createdAt}get hint(){return`${this.city??""}, ${this.provinceCode??this.provinceName??""}`}get label(){return` ${this.street??""}, ${this.city??""}, ${this.postcode??""}, ${this.provinceCode??this.provinceName??""}`}get formatted(){return`${this.company}, ${this.label}`}get fullName(){return`${this.firstName??""} ${this.lastName??""}`.trim()}get reference(){var t;return this.googleId||this.company||this.street||(((t=this.coordinates)==null?void 0:t.toString())??"")}toJson(){var t,e,i,o;return{id:this.id,googleId:this.googleId,firstName:this.firstName,lastName:this.lastName,emailAddress:this.emailAddress,phoneNumber:this.phoneNumber,company:this.company,street:this.street,city:this.city,provinceCode:this.provinceCode,provinceName:this.provinceName,postcode:this.postcode,countryCode:this.countryCode,coordinates:(t=this.coordinates)==null?void 0:t.toJson(),arriveAt:(e=this.arriveAt)==null?void 0:e.toISOString(),updatedAt:(i=this.updatedAt)==null?void 0:i.toISOString(),createdAt:(o=this.createdAt)==null?void 0:o.toISOString()}}static fromJson(t){return new d({id:t.id,googleId:t.googleId,firstName:t.firstName,lastName:t.lastName,emailAddress:t.emailAddress,phoneNumber:t.phoneNumber,company:t.company,street:t.street,city:t.city,provinceCode:t.provinceCode,provinceName:t.provinceName,postcode:t.postcode,countryCode:t.countryCode,coordinates:t.coordinate?u.fromJson(t.coordinate):void 0,arriveAt:t.arriveAt?new Date(t.arriveAt):void 0,updatedAt:t.updatedAt?new Date(t.updatedAt):void 0,createdAt:t.createdAt?new Date(t.createdAt):void 0})}static fromPlaceDetails(t){console.log("STARTED");const e=t.geometry.location,i=t.address_components,o={city:"",cityCode:"",provinceName:"",provinceCode:"",country:"",countryCode:"",streetName:"",streetNumber:"",postcode:""},n={street_number:"streetNumber",route:"streetName",postal_code:"postcode",locality:"city",administrative_area_level_1:"province",country:"country"};for(const c of i??[])for(const N of c.types){const a=n[N];a&&(o[a]=c.long_name,o[`${a}Name`]=c.long_name,o[`${a}Code`]=c.short_name)}t.formatted_address;const h=`${o.streetNumber} ${o.streetName}`.trim();return new d({company:t.name,googleId:t.place_id,firstName:"",lastName:"",emailAddress:"",phoneNumber:"",street:h,city:o.city,provinceName:o.provinceName,provinceCode:o.provinceCode,countryCode:o.countryCode,postcode:o.postcode,coordinates:u.fromJson({latitude:e.lat(),longitude:e.lng()})})}}async function I({first:s,after:t,filter:e,sort:i}={},{apollo:o}={}){o??(o=p());const{data:{address_connection:n},errors:h,error:c}=await o.query({query:f,variables:{first:s,after:t,filter:e,sort:i}});return y.fromJson({...n,buildNode:a=>d.fromJson(a)})}async function b({id:s},{apollo:t}={}){t??(t=p());const{data:{address:e},errors:i,error:o}=await t.query({query:C,variables:{id:s}});return d.fromJson(e)}async function T({input:s},{apollo:t}={}){t??(t=p());const{data:{address:e},errors:i}=await t.mutate({mutation:$,variables:{input:s}});return d.fromJson(e)}async function R({id:s,input:t},{apollo:e}={}){e??(e=p());const{data:{product:i},errors:o}=await e.mutate({mutation:S,variables:{input:t,id:s}});return d.fromJson(i)}const v=`
latitude
longitude
`,l=`
id
        firstName
        lastName
        emailAddress
        phoneNumber
        company
        street
        city
        provinceCode
        provinceName
        postcode
        countryCode
        coordinate{
          ${v}
        }
        updatedAt
        createdAt
`,C=m`
      query FETCH_ADDRESS_ITEM($id: Ulid!){
        address: get_address_item(id: $id){
          ${l}
        }
      }
`,f=m`query FETCH_ADDRESS_CONNECTION(
  $first: Int
  $after: String
  $filter: String
  $sort: String
){
  address_connection: get_address_list(
    first: $first
    after: $after
    filter: $filter
    sort: $sort
  ){
    totalCount
    pageInfo{
      hasNextPage
      hasPreviousPage
      startCursor
      endCursor
    }
    edges{
      cursor
      node{
        ${l}
      }
    }
  }
} 
`,$=m`
mutation CRAETE_ADDRESS($input: AddressCreationInput!){
  address: createNewAddress(
    input: $input
  ) {
   ${l}
  }
}
`,S=m`
mutation UPDATE_ADDRESS( $id: Ulid! $input: AddressUpdateInput!){
  product: updateAddress(
    id: $id
    input: $input
  ) {
   ${l}
  }
}
`;export{d as A,u as C,b as a,l as b,T as c,I as g,R as u};
